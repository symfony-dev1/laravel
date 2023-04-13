<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use Validator;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;
use App\Models\Variant;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $products = Product::with(["product_attribute_terms"])->get();
            return $this->sendResponse(true, 'Products retrieved successfully.', ProductResource::collection($products));
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'title' => 'required',
                'description' => 'required',
                'slug' => 'required',
                'price' => 'required',
                'quantity' => 'required'

            ]);

            if ($validator->fails()) {
                return $this->sendResponse(false, 'Validation Error.', $validator->errors());
            }

            $product = Product::create($input);

            return $this->sendResponse(true, 'Product created successfully.');
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = Product::find($id);
            if (is_null($product)) {
                return $this->sendResponse(false, 'Product not found.');
            }
            return $this->sendResponse(true, 'Product retrieved successfully.', new ProductResource($product));
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => 'required',
                'detail' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendResponse(false, 'Validation Error.', $validator->errors());
            }

            $product->name = $input['name'];
            $product->detail = $input['detail'];
            $product->save();

            return $this->sendResponse(true, 'Product updated successfully.');
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return $this->sendResponse(true, 'Product deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }

    public function getBySlug($slug)
    {
        try {
            $product = Product::with("variants.variant_attribute_terms.attribute")->where("slug", $slug)->first();
            if (is_null($product)) {
                return $this->sendResponse(false, 'Product not found.', [], 404);
            }
            $attrData = [];
            $noOfAttrData = 0;
            if (count($product["variants"]) > 0) {

                // foreach ($product["variants"] as $vKey => $variant) {
                //     foreach ($variant["variant_attribute_terms"] as $vatKey => $vat) {
                //         $attributeName = $vat["attribute"]["name"];
                //         if (!isset($variants["variants"][$attributeName][$vat["name"]])) {
                //             $variants["variants"][$attributeName][] = $vat["name"];
                //         }
                //     }
                // }

                foreach ($product["variants"] as $vKey => $variant) {
                    foreach ($variant["variant_attribute_terms"] as $vatKey => $vat) {
                        $attributeName = $vat["attribute"]["name"];
                        $attributeTermId = $vat["id"];

                        if (!isset($attrData[$attributeName][$vat["name"]])) {
                            $attrData[$attributeName][$attributeTermId] = $vat["name"];
                        }
                    }
                }
                $noOfAttrData = count($attrData);
            }
            return $this->sendResponse(true, 'Product retrieved successfully.', ["product" => $product, "attrData" => $attrData, "noOfAttrData" => $noOfAttrData]);

            // return $this->sendResponse(true, 'Product retrieved successfully.', new ProductResource($product));
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }

    public function checkVariantAvail(Request $request)
    {
        try {
            $attributeField = $request->attributeField;
            $noOfAttrData = $request->noOfAttrData;

            $variant_id = DB::table('attribute_term_variant')
                ->select('variant_id')
                ->whereIn('attribute_term_id', $attributeField)
                ->groupBy('variant_id')
                ->havingRaw('COUNT(DISTINCT attribute_term_id) =' . $noOfAttrData)
                ->value('variant_id');
            if (!is_null($variant_id)) {
                $variant = Variant::find($variant_id);
                return $this->sendResponse(true, 'Variant Get Successfully.', ["variant" => $variant]);
            } else {
                return $this->sendResponse(false, 'Out of Stock');
            }
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }
}
