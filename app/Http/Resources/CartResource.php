<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Variant;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VariantResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->cart_id,
            "cart" => $this->cart,
            "product_id" => $this->product_id,
            "variant_id" => $this->variant_id,
            "quantity" => $this->quantity,
            "image" => $this->image,
            // "product" => new ProductResource(Product::where("id", $this->product_id)->first()),
            // "variant" => $this->variant_id ? new VariantResource(Variant::where("id", $this->variant_id)->first()) : null,
            "product" => $this->product,
            "variant" => $this->variant
        ];
        // return [
        //     "id" => $this->id,
        //     "user_id" => $this->user_id,
        //     "uu_id" => $this->uu_id,
        //     "product_id" => $this->product_id,
        //     "variant_id" => $this->variant_id,
        //     "quantity" => $this->quantity,
        //     "image" => $this->image,
        //     "product" => new ProductResource(Product::where("id", $this->product_id)->first()),
        //     "variant" => $this->variant_id ? new VariantResource(Variant::where("id", $this->variant_id)->first()) : null,
        // ];
    }
}
