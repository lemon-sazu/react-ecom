<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_qty = $request->product_qty;

            $productCheck = Product::where('id', $product_id)->first();
            if ($productCheck) {

                if (Cart::where('product_id', $product_id)->where('user_id', $user_id)->exists()) {
                    return response()->json([
                        'status' => 409,
                        'message' => 'Already in Cart'
                    ]);
                } else {
                    $cart = new Cart();
                    $cart->user_id = $user_id;
                    $cart->product_id = $product_id;
                    $cart->product_qty = $product_qty;
                    if ($cart->save()) {
                        return response()->json([
                            'status' => 201,
                            'message' => 'Successfully Added on Cart.'
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product not Found.'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to Add to Cart'
            ]);
        }
    }
    public function cart()
    {
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $cart = Cart::where('user_id', $user_id)->get();
            return response()->json([
                'status' => 200,
                'cart' => $cart
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to View Cart Data'
            ]);
        }
    }
    public function cartUpdate($id, $scop)
    {

        if (auth('sanctum')->check()) {
            if ($scop == 'increment') {
                $cart = Cart::where('id', $id)->first();
                $cart->product_qty = $cart->product_qty + 1;
                if ($cart->save()) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Cart Updated Successfully.'
                    ]);
                }
            } else if ($scop == 'decrement') {
                $cart = Cart::where('id', $id)->first();
                if ($cart->product_qty > 1) {
                    $cart->product_qty = $cart->product_qty - ($cart->product_qty > 1 ? 1 : 0);
                    if ($cart->save()) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Cart Updated Successfully.'
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 409,
                        'message' => 'Minimum Quantity is 1.'
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to View Cart Data'
            ]);
        }
    }
    public function cartDelete($id)
    {
        if (auth('sanctum')->check()) {
            $cart = Cart::findOrFail($id);
            if ($cart->delete()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Product Deleted Successfully.'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Cart Not Found!'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to View Cart Data'
            ]);
        }
    }
}
