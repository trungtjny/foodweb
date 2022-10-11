<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = Order::where('user_id', Auth::id())->orderBy('updated_at', 'desc')->get();
        return $orders;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
       
        $input['user_id'] = Auth::id();
        $totalPrice = 0;
        $order = Order::create($input); 
        $cartItem = Cart::where('user_id',Auth::id())->get();
        if(count($cartItem)){
            foreach($cartItem as $item){
                    $price = $item->products->price;
                    $totalPrice += $price;
                    OrderItem::create([
                         'order_id' => $order->id,
                         'product_id' =>$item->product_id,
                         'quantity' =>$item->quantity,
                         'price' => $price,
                    ]);
                    $productItem = Product::findOrFail($item->product_id);
                    $productItem->sold = $productItem->sold + $item->quantity;
                    $productItem->save();
                    $item->delete();
                }
            $order->totalPrice = $totalPrice;
            $order->save();

            $user = User::findOrFail(Auth::id());
            if(empty($user->address)) $user->address = $input['address'];
            $user->save();
            return $order;
        }
        else {
            return ["error" => "Lỗi - giỏ hàng trống"];
        }
    }

    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::where('id',$request->id)->where('user_id', Auth::id())->first();
        if($order->status == Order::PREPARE){
            $order->update($request->input());
            return response()->json( ['status' => "Cập nhật thông tin thành công!",'result' => "true"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::where('id',$id)->where('user_id', Auth::id())->first();
        if($order->status <= Order::PREPARE ){
            foreach($order->orderItems as $item){
                $item->products->sold -= $item->quantity;
                $item->products->save();
            }
            $order->status = Order::FAIL;
            $order->save();
            return response()->json( ['status' => "Huỷ đơn hàng thành công!",'result' => "true"]);
        }
    }

    public function checkout(){

    }
}
