<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        $ordersResource = OrderResource::collection($orders);

        return response()->json($ordersResource);
    }
}
