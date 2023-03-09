<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return response()->json([
            'status' => 200,
            'category' => $category
        ]);
    }
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'slug' => 'required|max:191',
            'meta_title' => 'required|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->messages()
            ]);
        }

        $category = new Category();
        $category->name = $request->input('name');
        $category->slug = Str::slug($request->input('slug'));
        $category->description = $request->input('description');
        $category->status = $request->input('status');
        $category->meta_title = $request->input('meta_title');
        $category->meta_keyword = $request->input('meta_keyword');
        $category->meta_description = $request->input('meta_description');
        $category->save();
        return response()->json([
            'status' => 200,
            'message' => 'Category Added Successfully.'
        ]);
    }
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'category' => 'No Category Id Found'
            ]);
        }
    }
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'slug' => 'required|max:191',
            'meta_title' => 'required|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validator->messages()
            ]);
        } else {
            $category = Category::findOrFail($id);
            if ($category) {

                $category->name = $request->input('name');
                $category->slug = Str::slug($request->input('slug'));
                $category->description = $request->input('description');
                $category->status = $request->input('status');
                $category->meta_title = $request->input('meta_title');
                $category->meta_keyword = $request->input('meta_keyword');
                $category->meta_description = $request->input('meta_description');
                $category->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Category Added Successfully.'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Category Found with This ID'
                ]);
            }
        }
    }
    public function delete($id)
    {
        $category = Category::findOrFail($id);
        if ($category) {
            $category->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Category Deleted Successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Category Not Found'
            ]);
        }
    }
    public function get_category()
    {
        $category = Category::where('status', 1)->get();
        return response()->json([
            'status' => '200',
            'category' => $category
        ]);
    }
}
