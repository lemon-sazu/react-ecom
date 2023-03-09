<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => 200,
            'products' => $products
        ]);
    }
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        if ($product) {

            return response()->json([
                'status' => 200,
                'product' => $product
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Product Found!'
            ]);
        }
    }
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required|numeric',
            'slug' => 'required',
            'meta_title' => 'required',
            'brand' => 'required',
            'selling_price' => 'required',
            'original_price' => 'required',
            'qty' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        } else {
            $product = Product::findOrFail($id);
            if ($product) {

                $product->name = $request->input('name');
                $product->slug = Str::slug($request->input('slug')) ;
                $product->description = $request->input('description');
                $product->brand = $request->input('brand');
                $product->selling_price = $request->input('selling_price');
                $product->original_price = $request->input('original_price');
                $product->qty = $request->input('qty');
                $product->category_id = $request->input('category_id');
                $product->meta_title = $request->input('meta_title');
                $product->meta_keyword = $request->input('meta_keyword');
                $product->meta_description = $request->input('meta_description');

                $product->featured = $request->input('featured') == true ? '1' : '0';
                $product->popular = $request->input('popular') == true ? '1' : '0';
                $product->status = $request->input('status') == true ? '1' : '0';

                if ($request->hasFile('image')) {
                    $path = $product->image;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extention = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extention;
                    $file->move('uploads/product/', $filename);
                    $product->image = 'uploads/product/' . $filename;
                }
                if ($product->save()) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Product Updated Successfully.'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product Not Found'
                ]);
            }
        }
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required|numeric',
            'slug' => 'required',
            'meta_title' => 'required',
            'brand' => 'required',
            'selling_price' => 'required',
            'original_price' => 'required',
            'qty' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        } else {
            $product = new Product();
            $product->name = $request->input('name');
            $product->slug = Str::slug($request->input('slug'));
            $product->description = $request->input('description');
            $product->brand = $request->input('brand');
            $product->selling_price = $request->input('selling_price');
            $product->original_price = $request->input('original_price');
            $product->qty = $request->input('qty');
            $product->category_id = $request->input('category_id');
            $product->meta_title = $request->input('meta_title');
            $product->meta_keyword = $request->input('meta_keyword');
            $product->meta_description = $request->input('meta_description');

            $product->featured = $request->input('featured') == true ? '1' : '0';
            $product->popular = $request->input('popular') == true ? '1' : '0';
            $product->status = $request->input('status') == true ? '1' : '0';

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extention = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extention;
                $file->move('uploads/product/', $filename);
                $product->image = 'uploads/product/' . $filename;
            }
            if ($product->save()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Product Created Successfully.'
                ]);
            }
        }
    }
}
