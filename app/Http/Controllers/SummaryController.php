<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function index()
    {
        $products = Product::count();
        $orders = Order::count();
        $order_processed = Order::where('processed',true)->count();

        return response()->json([
            'products' => $products,
            'orders' => $orders,
            'order_processed' => $order_processed
        ]);
    }
}
