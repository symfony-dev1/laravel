<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Tag;
use Validator;
use App\Models\Attribute;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $products = Product::with("categories", "tags", "brands")->sortable();
        $search = $request->search;
        $stock_action_input = $request->stock_action_input;

        if ($search && $search != "") {
            $products = $products->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')->orWhere('price', 'like', '%' . $search . '%');
            });
        }
        if ($stock_action_input && $stock_action_input != "") {
            $products = $products->where(function ($query) use ($stock_action_input) {
                $query->where('stock_status',  $stock_action_input);
            });
        }
        $products = $products->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $attributes =   $attributes = Attribute::all();

        $categories = Category::orderby("title", "asc")->get();
        $brands = Brand::orderby("title", "asc")->get();
        $tags = Tag::orderby("title", "asc")->get();

        return view('products.form', compact('categories', "brands", "tags", "attributes"));
    }

    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'product_title' => 'required',
                    "price" => "required",
                    "sku" => "required",
                ]
            );

            if (empty($request->product_slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->product_title)));
            } else {
                $slug = $request->product_slug;
            }

            if ($request->edit_id && $request->edit_id != "") {
                $product =  Product::where("id", $request->edit_id)->first();
                $checkExist = Product::where("slug", $slug)->where("id", "!=", $request->edit_id)->first();
            } else {
                $product = new Product;
                $checkExist = Product::where("slug", $slug)->first();
            }


            // $checkExist = Category::where("slug", $slug)->first();
            if ($checkExist) {
                if ($request->edit_id && $request->edit_id != "") {
                    $check = Product::where("slug", "like",  $slug . "-%")->where("id", "!=", $request->edit_id)->first();
                } else {
                    $check = Product::where("slug", "like",  $slug . "-%")->first();
                }
                if ($check) {
                    $string = $check->slug;
                    $exploded = explode('-', $string);
                    $no = end($exploded);
                    $s = $slug . "-" . $no + 1;
                } else {
                    $s = $slug . "-1";
                }
            } else {
                $s = $slug;
            }

            if ($request->hasFile('image')) {
                if ($request->edit_id && $request->edit_id != "" && $product->product_image != "") {
                    if (file_exists(public_path('uploads/products/' . $product->product_image))) {
                        unlink(public_path('uploads/products/' . $product->product_image));
                    }
                }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = "product-image-" . $s . "-" . time() . '.' . $extension;
                $file->move(public_path('uploads/products/'), $filename);
                $product->product_image = $filename;
                $product->img_alt = $request->imgalt;
                $product->img_caption = $request->caption;
            }
            $product->title = $request->product_title;
            $product->slug = $s;
            $product->description = $request->product_description;
            $product->short_description = $request->product_short_description;
            $product->product_type = $request->product_type;
            $product->price = $request->price;
            $product->sales_price = $request->sale_price;
            $product->start_date = $request->start_date;
            $product->end_date = $request->end_date;
            $product->sku    = $request->sku;
            $product->stock = $request->stock;
            $product->stock_status = 1;
            $product->allow_backorders = $request->allow_backorders;
            $product->dim_width = $request->weight;
            $product->dim_height = $request->height;
            $product->dim_length = $request->length;
            $product->weight = $request->weight;
            $product->status = $request->status;
            $product->quantity = $request->product_quantity;
            $product->primary_cat_id = $request->primary_cat_id;
            $product->quantity = 23;
            $product->sold_individually = 0;
            $product->enable_reviews = 1;

            $product->save();
            if (isset($request->categories)) {
                $product->categories()->sync($request->categories);
            } else {
                $product->categories()->detach();
            }

            if (isset($request->brands)) {
                $product->brands()->sync($request->brands);
            } else {
                $product->brands()->detach();
            }
            if (isset($request->tags)) {
                $product->tags()->sync($request->tags);
            } else {
                $product->tags()->detach();
            }

            if (isset($request->tags)) {
                $product->tags()->sync($request->tags);
            } else {
                $product->tags()->detach();
            }

            if ($request->edit_id && $request->edit_id != "") {
                return redirect()->back()->with("success", "Product updated successfully."); //, '');
            } else {
                return redirect()->back()->with("success", "Product created successfully."); //, '');
            }
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with("error", "Something went wrong!"); //, '');
        }
    }

    public function createProductSlug(Request $request)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->product_title)));
        return response()->json($slug);
    }
    public function bulkAction(Request $request)
    {
        $selectedIds =  $request->selectedIds;
        $action = $request->action;
        if ($action == "delete") {
            Product::whereIn("id", $selectedIds)->delete();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::where("id", $id)->delete();
    }
    public function updateProductSlug(Request $request)
    {
        $slug =  $request->update_slug;

        $check = Product::where("slug", $slug)->where("status", 3)->first();
        if (!$check) {
            return response()->json("success");
        }
    }

    public function edit($id)
    {
        $attributes =   $attributes = Attribute::all();
        $product = Product::with("categories", "tags", "brands")->where("id", $id)->first();
        $product_categories = $product->categories->pluck('id');
        $categories = Category::orderby("title", "asc")->get();
        $brands = Brand::orderby("title", "asc")->get();
        $tags = Tag::orderby("title", "asc")->get();
        return view('products.form', compact('categories', "brands", "tags", "attributes", "product"));
    }
    public function show($id)
    {
        $product = Product::with("categories", "tags", "brands")->where("id", $id);
        return response()->json($product);
    }
}
