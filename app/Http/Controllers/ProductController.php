<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\Rule;
use MongoDB\BSON\ObjectId;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $productsResource = ProductResource::collection($products);

        return response()->json($productsResource);
    }

    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Check detail product failed',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:products|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0'
        ],[
            'name.unique' => 'Name already exists'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        try {
            $product = Product::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Product created successfully',
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product create failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'unique:products,name,'.$id.',_id',
            'price' => 'numeric'
        ],[
            'name.unique' => 'Name already exists'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        try {
            $product = Product::findOrFail($id);

            $product->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully',
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try{
            $product = Product::findOrFail($id);
            // delete the product
            $product->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully',
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product delete failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
