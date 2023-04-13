<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Variant;
use App\Http\Resources\VariantResource;

class ProductResource extends JsonResource
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
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "description" => $this->description,
            "price" => $this->price,
            "quantity" => $this->quantity,
            "product_image" => $this->product_image,
            "product_attribute_terms" => $this->product_attribute_terms,
            // "variants" =>  VariantResource::collection(Variant::with(["variant_attribute_terms"])->where("product_id", $this->id)->get())
            "variants" =>   $this->variants //Variant::with("variant_attribute_terms")->where("product_id", $this->id)->get()
        ];
    }
}
