<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\Variant;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            // "cart" => $this['cart'],
            "product_id" => $this->product_id,
            "variant_id" => $this->variant_id,
            "quantity" => $this->quantity,
            "image" => $this->image,
            // "product" => new ProductResource(Product::where("id", $this->product_id)->first()),
            // "variant" => $this->variant_id ? new VariantResource(Variant::where("id", $this->variant_id)->first()) : null,
        ];
    }
}
