<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function test()
    {
        // $variants = DB::table("attribute_term_variant")->->whereIn("attribute_term_id", [1, 3])->distinct()->get();
        // dd($variants);



        $product = Product::with("variants.variant_attribute_terms.attribute")->where("slug", "lenovo-laptop")->first()->toArray();
        if (is_null($product)) {
            return 'Product not found.';
        }

        foreach ($product["variants"] as $vKey => $variant) {
            // dump($variant);
            // $attributeName = $variant["variant_attribute_terms"][$vKey]["attribute"]["name"];
            // dump($attributeName);
            foreach ($variant["variant_attribute_terms"] as $vatKey => $vat) {
                $attributeName = $vat["attribute"]["name"];
                $attributeTermId = $vat["id"];

                // dd($attributeName);
                if (!isset($main["variants"][$attributeName][$vat["name"]])) {
                    // dd($vat);

                    $main["variants"][$attributeName][$attributeTermId] = $vat["name"];
                }
            }
        }


        dd($main);
    }
}
