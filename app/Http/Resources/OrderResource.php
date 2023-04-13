<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
        if ($this->order_status == 0) {
            $bgClass = " bg-warning";
            $status = "PENDING";
        } elseif ($this->order_status == 1) {
            $bgClass = " bg-info";
            $status = "PROCESSING";
        } elseif ($this->order_status == 2) {
            $bgClass = " bg-secondary";
            $status = "ON HOLD";
        } elseif ($this->order_status == 3) {
            $status = "COMPLETED";
            $bgClass = " bg-success";
        } elseif ($this->order_status == 4) {
            $bgClass = " bg-danger";
            $status = "CANCELLED";
        } elseif ($this->order_status == 5) {
            $bgClass = " bg-danger";
            $status = "FAILED";
        } elseif ($this->order_status == 6) {
            $status = "DRAFT";
            $bgClass = " bg-dark";
        }

        return [
            "bgClass" => $bgClass,
            "id" => $this->id,
            "order_status" => $status,
            "created_at" =>  date("d-m-Y", strtotime($this->created_at)),
            "total_amount" => $this->total_amount,
            "items" =>   $this->items,
            "shipping_first_name" => $this->shipping_first_name,
            "shipping_last_name" => $this->shipping_last_name,
            "shipping_country" => $this->shipping_country,
            "shipping_state" => $this->shipping_state,
            "shipping_pincode" => $this->shipping_pincode,
            "email" => $this->email,
            "phone_no" => $this->phone_no,
            "address" => $this->shipping_street_address_line_1
        ];
    }
}
