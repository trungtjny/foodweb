<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Shop::get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
        $validator = $this->validateData($request->all());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return Shop::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Shop::findOrFail($id);
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
        $validator = $this->validateData($request->all());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $shop = Shop::findOrFail($id);
        if ($shop) {
            return $shop->update($request->all());
        } else {
            return false;
        }
    }

    public function destroy($id)
    {
        return Shop::findOrFail($id)->delete();
    }

    public function validateData($data)
    {
        return Validator::make($data, [
            'address' => 'required|unique:shops,address',
        ]);
    }
}
