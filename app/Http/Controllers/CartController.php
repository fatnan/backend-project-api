<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Jobs\ProcessOrder;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'product_id' => 'required|exists:products,_id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $product = Product::find($request['product_id']);

        if($product){
            if($request['quantity'] > $product['quantity']){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Quantity max '.$product['quantity']
                ]);
            }

            // check if product exists at cart
            $cart = Cart::where('product_id',$request['product_id'])->first();

            if($cart){
                $cart->quantity = $request['quantity'];
                $cart->save();
            } else {
                $cart = Cart::create([
                    'product_id' => $request['product_id'],
                    'quantity' => $request['quantity']
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'create cart failed, no Product exists with product_id '.$request['product_id'],
            ], 500);
        }


        return response()->json($cart);
    }

    public function checkout(Request $request)
    {
        $carts = Cart::all();
        $products = [];

        foreach ($carts as $cart) {
            $product = Product::findOrFail($cart->product_id);

            $products[] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'quantity' => $cart->quantity,
                'total_price' => $product->price * $cart->quantity
            ];

            $cart->delete();
        }

        $order = Order::create([
            'customer_name' => $request['customer_name'],
            'customer_email' => $request['customer_email'],
            'products' => $products
        ]);

        // Dispatch a ProcessOrder job to the queue for background processing
        dispatch(new ProcessOrder($order));

        return response()->json($order);
    }

}
