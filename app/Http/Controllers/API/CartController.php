<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Validator;
use App\Models\Option;
use App\Models\CartItem;

class CartController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $header = $request->header('Authorization');
        try {
            $cart = [];
            $header = $request->header('Authorization');
            if ($header && $header != "") {
                $ID = $request->Id;
                $cartId = Cart::where("user_id", $ID)->value("id");
                $cart = CartItem::with(["product.product_attribute_terms.attribute", "variant.variant_attribute_terms.attribute"])->where("cart_id", $cartId)->get();
            } else {
                if ($request->hasCookie('_cg_id')) {
                    $ID = $request->cookie('_cg_id');
                    $cartId = Cart::where("guest_id", $ID)->value("id");
                    // $cart = CartItem::where("cart_id", $cartId)->get();
                    $cart = CartItem::with(["product.product_attribute_terms.attribute", "variant.variant_attribute_terms.attribute"])->where("cart_id", $cartId)->get();
                }
            }
            return $this->sendResponse(true, 'Cart retrieved successfully.',  CartResource::collection($cart));
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }

    public function cartCalculation(Request $request)
    {
        $cartsCal = CartItem::select("cart_items.*", "p.price as productPrice", "v.price as variantPrice", "v.sale_price")
            ->join("products as p", "p.id", "=", "cart_items.product_id")
            ->leftjoin("variants as v", "v.id", "=", "cart_items.variant_id")
            ->where("cart_items.cart_id", $request->cart_id)->get();
        // return response()->json($cartsCal);

        $subTotal = 0;
        $total = 0;
        $shippingCharge = 0;
        foreach ($cartsCal as $ke => $st) {
            if ($st->variantPrice != null) {
                $subTotal += $st->variantPrice * $st->quantity;
            } else {
                $subTotal += $st->productPrice * $st->quantity;
            }
        }
        $shippingCharge = 10;
        // Option::where("oprion_key", "shipping_charge")->value("option_value");
        $total = $subTotal + $shippingCharge;
        return $this->sendResponse(true, 'Cart calculation retrieved successfully.', ["subTotal" => $subTotal, "shippingCharge" => $shippingCharge, "total" => $total]);
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
     * @param  \App\Http\Requests\StoreCartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return response()->json($request);
        try {
            $validator = Validator::make($request->all(), [
                "product_id" => "required",
                "quantity" => "required"
            ]);

            if ($validator->fails()) {
                return $this->sendResponse(false, 'All fields are required', $validator->errors());
            } else {
                $productId = $request->product_id;
                $variantId = $request->variant_id;
                $quantity = $request->quantity;

                $header = $request->header('Authorization');

                // return $this->sendResponse(true, 'This product added to cart successfully.', [], 200, $cookieSet);
                $cookieSet = [];
                if ($header && $header != "") {
                    $userId = $request->user_id;
                    $cart = Cart::where('user_id', $userId)->first();
                    if (!$cart) {
                        $cart = new Cart();
                        $cart->id = Str::uuid();
                        $cart->user_id = $userId;
                        $cart->save();
                    }
                } else {
                    $cg_id = $request->cookie('_cg_id');
                    if (!$request->hasCookie('_cg_id')) {
                        $cg_id = Str::uuid()->toString();
                        $cookieSet = ["name" => "_cg_id", "value" => $cg_id, "minutes" => 60];
                    }
                    $cart = Cart::where('guest_id', $cg_id)->first();
                    if (!$cart) {
                        $cart = new Cart();
                        $cart->id = Str::uuid();
                        $cart->guest_id = $cg_id;
                        $cart->save();
                    }
                }
                if ($variantId) {
                    $item = CartItem::where("cart_id", $cart->id)->where("product_id", $productId)->where("variant_id", $variantId)->first();
                } else {
                    $item = CartItem::where("cart_id", $cart->id)->where("product_id", $productId)->first();
                }
                if (!$item) {
                    $item = new CartItem;
                    $item->quantity = $quantity;
                } else {
                    $item->quantity += $quantity;
                }
                $item->cart_id = $cart->id;
                $item->variant_id = $variantId ? $variantId : null;
                $item->product_id = $productId;
                $item->image = Product::where("id", $productId)->value("product_image");
                $item->save();



                return $this->sendResponse(true, 'This product added to cart successfully.', [], 200, $cookieSet);

                // if ($header && $header != "") {
                //     // user is logged in, store cart data in database
                //     // store cart data in database
                //     if (Cart::where("product_id", $productId)->where("user_id", $request->user_id)->first()) {
                //         $cart = Cart::where("product_id", $productId)->where("user_id", $request->user_id)->first();
                //         $cart->quantity += $quantity;
                //     } else {
                //         $cart = new Cart;
                //         $cart->quantity = $quantity;
                //     }
                //     $cart->id = Str::uuid();
                //     $cart->user_id = $request->user_id;
                //     $cart->product_id = $productId;
                //     $cart->image = Product::where("id", $productId)->value("product_image");
                //     $cart->save();
                //     return $this->sendResponse(true, 'This product added to cart successfully.');
                // } else {
                //     // return response('Hello World')->withCookie(cookie('name', 'value', $minutes));
                //     $uuid = $request->cookie('_cg_id');
                //     if ($uuid == null) {
                //         $uuid = Str::uuid()->toString();
                //     }
                //     if (Cart::where("product_id", $productId)->where("uu_id", $uuid)->first()) {
                //         $cart = Cart::where("product_id", $productId)->where("uu_id", $uuid)->first();
                //         $cart->quantity += $quantity;
                //     } else {
                //         $cart = new Cart;
                //         $cart->quantity = $quantity;
                //     }
                //     $cart->id = Str::uuid();
                //     $cart->product_id = $productId;
                //     $cart->image = Product::where("id", $productId)->value("product_image");
                //     $cart->save();
                //     $cookieSet = ["name" => "_cg_id", "value" => $uuid, "minutes" => 60];
                //     return $this->sendResponse(true, 'This product added to cart successfully.', [], 200, $cookieSet);
                // }
            }
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCartRequest  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }

    public function onChangeQuantity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "quantity" => "required",
            "cart_id" => "required",
            "product_id" => "required"
        ]);
        if ($validator->fails()) {
            return $this->sendResponse(false, 'Something went wrong.', $validator->errors());
        } else {
            if ($request->variant_id == null) {
                $cart = CartItem::where("cart_id", $request->cart_id)->where("product_id", $request->product_id)->first();
                $cart->quantity = $request->quantity;
                $cart->save();
            } else {
                $cart = CartItem::where("cart_id", $request->cart_id)->where("product_id", $request->product_id)->where("variant_id", $request->variant_id)->first();
                $cart->quantity = $request->quantity;
                $cart->save();
            }

            return $this->sendResponse(true, 'Cart updated successfully.');
        }
    }
    public function removeItemCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "cart_id" => "required",
        ]);
        if ($validator->fails()) {
            return $this->sendResponse(false, 'Something went wrong.', $validator->errors());
        } else {
            // return $request;
            if ($request->removeAll) {
                Cart::where("id", $request->cart_id)->delete();
            } else {
                if ($request->variant_id) {
                    CartItem::where("cart_id", $request->cart_id)->where("product_id", $request->product_id)->where("variant_id", $request->variant_id)->delete();
                } else {
                    CartItem::where("cart_id", $request->cart_id)->where("product_id", $request->product_id)->delete();
                }
            }
            return $this->sendResponse(true, 'Item removed successfully.');
        }
    }
    public function cartGlobalCount(Request $request)
    {
        // $header = $request->header('Authorization');
        try {
            $globalQuantityCount = 0;
            $globalSubTotalAmount = 0;

            $header = $request->header('Authorization');
            if ($header && $header != "") {
                $ID = $request->user_id;
                $cartId = Cart::where("user_id", $ID)->value("id");
            } else {
                if ($request->hasCookie('_cg_id')) {
                    $ID = $request->cookie('_cg_id');
                    $cartId = Cart::where("guest_id", $ID)->value("id");
                }
            }
            $cartItems = CartItem::with("product", "variant")->where("cart_id", $cartId)->get();

            if (count($cartItems) > 0) {
                foreach ($cartItems as $ci) {
                    $globalQuantityCount += $ci->quantity;
                    if ($ci->variant) {
                        $globalSubTotalAmount += ($ci->quantity * $ci->variant->price);
                    } else {
                        $globalSubTotalAmount += ($ci->quantity * $ci->product->price);
                    }
                }
            }
            return $this->sendResponse(true, 'Cart gobal count retrieved successfully.',  ["globalQuantityCount" => $globalQuantityCount, "globalSubTotalAmount" => $globalSubTotalAmount]);
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }
}
