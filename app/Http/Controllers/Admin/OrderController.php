<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function updateStatus(Request $request, $id) {
        $order = Order::findOrFail($id);
        $order->update($request->only('message', 'status'));
    }

}
