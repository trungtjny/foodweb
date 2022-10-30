<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

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

    public function test(Request $request) {
        return $request->all();

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
                $options = $request->product_options;
                $cart = Cart::where('product_id',$product_id)->where('user_id',Auth::id())->first();
                if($cart){
                    if($cart->product_options == $options) {
                        $cart->product_options = $options;
                        $cart->quantity = $cart->quantity + $quantity;
                        $cart->save();
                        return response()->json( ['message' => "Cập nhật số lượng sản phẩm thành công",'status' => "true"]);
                    }
                }
                $input = $request->all();
                $input['user_id'] = Auth::id();
                $cartItem = Cart::create($input);
                $cartItem->product_options = $request->product_options;
                $cartItem->save(); 
                return response()->json(['message' => $product_check->name." đã được thêm vào giỏ hàng",'status' => "true"]);
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
        $cart = Cart::findOrFail($id);
        if($cart) {
            $cart->quantity = $request->quantity;
            $cart->save();
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
        $cart = Cart::where("id", $id)->first();
        if($cart) {
            $cart->delete();
            return responseSuccess(null , "Delete successfully", 200);
        } 
        return responseError(null,"error",400);
    }
    
}
