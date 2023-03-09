<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    public function category()
    {
        $category = Category::where('status', 1)->get();
        if ($category) {

            return response()->json([
                'status' => 200,
                'category' => $category
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Category Not Found'
            ]);
        }
    }
    public function product_by_cat($slug)
    {
        $category = Category::where('slug', $slug)->with('products', function ($product) {
            $product->where('status', 1);
        })->first();

        return response()->json(['status' => 200, 'category' => $category]);
    }
    public function singleProduct($slug){
        $product = Product::where('slug', $slug)->first();
        $related = Product::where('category_id', $product->category->id)->where('status', 1)->limit(10)->get();
        if($product){
            return response()->json([
                'status'=> 200,
                'product' => $product,
                'related' => $related
            ]);
        }else{
            return response()->json([
                'status'=> 404,
                'message' => 'Sorry! Product not found.'
            ]);
        }
    }
}
