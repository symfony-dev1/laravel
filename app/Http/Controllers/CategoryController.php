<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::with("childs")->sortable();

        $search = $request->search;
        if ($search && $search) {
            $categories = $categories->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        $categories = $categories->paginate(10);
        return view("categories/index", compact("categories"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                "category_title" => "required",
            ]
        );


        if (empty($request->slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->category_title)));
        } else {
            $slug = $request->slug;
        }

        if ($request->edit_id && $request->edit_id != "") {
            $category =  Category::where("id", $request->edit_id)->first();
            $checkExist = Category::where("slug", $slug)->where("id", "!=", $request->edit_id)->first();
        } else {
            $category = new Category;
            $checkExist = Category::where("slug", $slug)->first();
        }


        // $checkExist = Category::where("slug", $slug)->first();
        if ($checkExist) {
            if ($request->edit_id && $request->edit_id != "") {
                $check = Category::where("slug", "like",  $slug . "-%")->where("id", "!=", $request->edit_id)->first();
            } else {
                $check = Category::where("slug", "like",  $slug . "-%")->first();
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
        // dd($s);


        // if ($request->edit_id && $request->edit_id != "") {
        //     $id = $request->edit_id;
        //     $category =  Category::where("id", $id)->first();
        // } else {
        //     $category = new Category;
        // }
        if ($request->hasFile('image')) {
            if ($request->edit_id && $request->edit_id != "" && $category->category_image != "") {
                unlink(public_path('uploads/categories/' . $category->category_image));
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = "category-image-" . $s . "-" . time() . '.' . $extension;
            $file->move(public_path('uploads/categories/'), $filename);
            $category->category_image = $filename;
        }
        $category->title = $request->category_title;
        $category->description = $request->description;
        $category->parent_id = $request->parent_id ? $request->parent_id : null;
        $category->slug = $s; // strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->category_title)));
        $category->save();
        if ($request->edit_id && $request->edit_id != "") {
            return redirect()->back()->with("success", "Category updated successfully");
        } else {
            return redirect()->back()->with("success", "Category added successfully");
        }
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        return response()->json($category);

        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::where("id", $id)->first();
        if ($category->category_image != "") {
            unlink(public_path('uploads/categories/' . $category->category_image));
        }
        $category->delete();
    }
    public function bulkAction(Request $request)
    {
        $selectedIds =  $request->selectedIds;
        $action = $request->action;
        if ($action == "delete") {
            Category::whereIn("id", $selectedIds)->delete();
        }
    }
}
