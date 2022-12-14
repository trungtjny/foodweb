<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function updateStatus(Request $request, $id) {
        $order = Order::findOrFail($id);
        $order->update($request->only('message', 'status','name','phone','address'));
        return $order;
    }

    public function detail(Request $request, $id) {
        $order = Order::with('orderItems.products')->findOrFail($id);
        $order->update($request->only('message', 'status','name','phone','address'));
        return $order;
    }
    public function delete(Request $request, $id) {
        $order = Order::findOrFail($id);
        $order->delete();
        return $order;
    }
    public function getlist(Request $request) {
        $q = Order::query();
        if($request->status) {
            $q = $q->where('status', $request->status);
        } 
        return $q->with('orderItems.products')->orderBy('id', 'desc')->get();
    }

    public function getlistbyshopid(Request $request) {
        $q = Order::query();
        if($request->status) {
            $q = $q->where('status', $request->status);
        }   return $q->where('shop_id', $request->shop_id)->with('orderItems.products')->orderBy('id', 'desc')->get();
    }
}
