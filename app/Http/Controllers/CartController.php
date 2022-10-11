<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Cart::where('user_id',Auth::id())->select()->with(['products' => function($query){
            $query->select('name','id','thumb','price','options');
        } ])->get();
        foreach($data as $item) {
            unset($item['user_id']);unset($item);
        }
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');
        if(Auth::check()){
            $product_check = Product::where('id',$product_id)->first();
            if($product_check){
                if(Cart::where('product_id',$product_id)->where('user_id',Auth::id())->exists()){
                    $cart = Cart::where('product_id',$product_id)->where('user_id',Auth::id())->first();
                    $cart->quantity = $cart->quantity + $quantity;
                    $cart->save();
                    return response()->json( ['status' => "Sản phẩm đã thêm vào giỏ hàng",'result' => "true"]);
                }
                $cartItem = new Cart();
                $cartItem->product_id = $product_id;
                $cartItem->quantity = $quantity;
                $cartItem ->user_id = Auth::id();
                $cartItem->save();
                return response()->json(['status' => $product_check->name." đã được thêm vào giỏ hàng",'result' => "true"]);
            }
        }
        else {
            return response()->json( ['status' => "Vui lòng đăng nhập để tiếp tục.",'result' => "false"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');
        if(Cart::where('product_id',$product_id)->where('user_id',Auth::id())->exists())
        {
            $cartItem = Cart::where('product_id',$product_id)->where('user_id',Auth::id())->first();
            $cartItem->quantity = $quantity;
            $cartItem->update();
            return response()->json(['status' => "Cập nhật số lượng sản phẩm",'result' => "true"]);
        }
        else return response()->json(['status' => "Có lỗi trong quá trình thực hiện. Vui lòng thử lại sau!",'result' => "false"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $product_id = $request->input('product_id');
        if(Cart::where('product_id',$product_id)->where('user_id',Auth::id())->exists())
        {
            $cartItem = Cart::where('product_id',$product_id)->where('user_id',Auth::id())->first();
            $cartItem->delete();
            return response()->json(['status' => "Sản phẩm đã được gỡ khỏi giỏ hàng!",'result' => "true"]);
        }
        else return response()->json(['status' => "Có lỗi trong quá trình thực hiện. Vui lòng thử lại sau!",'result' => "false"]);
    }
}