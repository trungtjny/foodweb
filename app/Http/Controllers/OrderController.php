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
        $orders = Order::where('user_id', Auth::id())->with('orderItems.products')->orderBy('updated_at', 'desc')->get();
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
        $cartItem = Cart::where('user_id',Auth::id())->with('products')->get();
        if(count($cartItem)){
            $order = Order::create($input); 
            foreach($cartItem as $item ){
                    $productItem = Product::findOrFail($item->product_id);
                    $price = $item->products->options['size'][$item->product_options]['price']*$item->quantity;
                    $totalPrice += $price;
                    logger( $item->products->options['size'][$item->product_options]);
                    OrderItem::create([
                         'order_id' => $order->id,
                         'product_id' =>$item->product_id,
                         'quantity' =>$item->quantity,
                        //  'product_options' => $item->product_options,
                         'product_options' => $item->products->options['size'][$item->product_options],
                         'price' => $price,
                    ]);
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
            return responseError('fail','Lỗi - giỏ hàng trống', 424);
        }
    }

    public function show($id)
    {
        $order = Order::Where('id', $id)->with('orderItems.products')->get();
        return $order;
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
        else 
        return responseSuccess(false,"Cập nhật đơn hàng không thành công, liên hệ tổng đài để được hỗ trợ",424);
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
