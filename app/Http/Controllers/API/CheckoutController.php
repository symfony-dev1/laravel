<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Validator;
use App\Models\Variant;
use App\Models\Product;
use App\Models\Option;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends BaseController
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // return $request;
        try {
            $validator = Validator::make($request->all(), [
                "firstName" => "required",
                "lastName" => "required",
                "phone" => "required|numeric|digits:10",
                "city" => "required",
                "country" => "required",
                "paymentMethod" => "required",
                "email" => "required|email",
                "zip" => "required|numeric",
                "address" => "required",
                "state" => "required"

            ]);

            if ($validator->fails()) {
                return $this->sendResponse(false, 'All fields are required', $validator->errors());
            } else {
                $firstName = $request->firstName;
                $lastName = $request->lastName;
                $phone = $request->phone;
                $city = $request->city;
                $country = $request->country;
                $email = $request->email;
                $zip = $request->zip;
                $address = $request->address;
                $userId = $request->user_id;
                $shipping_charge = Option::where("option_key", "shipping_charge")->value("option_value");
                $state = $request->state;
                $cartId = Cart::where("user_id", $userId)->value("id");

                $order = new Order;
                $order->user_id = $userId;
                $order->billing_first_name = $order->shipping_first_name = $firstName;
                $order->billing_last_name = $order->shipping_last_name  = $lastName;
                $order->phone_no = $phone;
                $order->billing_city = $order->shipping_city = $city;
                $order->billing_state = $order->shipping_state = $state;
                $order->billing_country = $order->shipping_country = $country;
                $order->email = $email;
                $order->billing_street_address_line_1 = $order->shipping_street_address_line_1 = $address;
                $order->shipping_charge = $shipping_charge;
                $order->billing_pincode = $order->shipping_pincode = $zip;
                $order->order_status = 3;
                $order->total_amount = $request->total_amount;
                $order->billing_state = $request->state;
                $order->save();

                $orderId = $order->id;

                $cartItem =  CartItem::where("cart_id", $cartId)->get();
                foreach ($cartItem as $k => $v) {
                    $orderItem = new OrderItem;
                    $orderItem->order_id = $orderId;
                    $orderItem->product_id = $v->product_id;
                    $orderItem->variant_id = $v->variant_id;
                    $orderItem->quantity = $v->quantity;
                    $orderItem->unit_amount = Variant::where("id", $v->variant_id)->value("price") ? Variant::where("id", $v->variant_id)->value("price") : Product::where("id", $v->product_id)->value("price");
                    $orderItem->save();
                }
                Cart::where("id", $cartId)->delete();
                return $this->sendResponse(true, 'This Order confirmed successfully.');
            }
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
        //
    }
}
