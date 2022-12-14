<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $product = Product::query();
        $filter = $request->get('filter');
        if (!empty($filter['key'])) {
            $product = $product->where('name', 'like', '%' . $filter['key'] . '%')->orWhere('description', 'like', '%' . $filter['key'] . '%');
        }
        if (!empty($filter['sortby'])) {
            $product = $product->orderBy($filter['sortby'], $filter['sortop']);
        }
        //logger($product->toSql());
        return $product->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $input = $request->input();
        //logger($input);
        if ($request->hasFile('thumb')) {
            $image = $request->file('thumb');
            $type = $request->file('thumb')->extension();
            $image_name = time() . '-product.' . $type;
            $path = Storage::disk('local')->put('/public/product/' . $image_name, $image->getContent());
            $input['thumb'] = 'storage/product/' . $image_name;
        }
        
        return Product::create($input);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $input = $request->all();
        if ($request->hasFile('thumb')) {
            $image = $request->file('thumb');
            $type = $request->file('thumb')->extension();
            $image_name = time() . '-product.' . $type;
            $path = Storage::disk('local')->put('/public/product/' . $image_name, $image->getContent());
            $input['thumb'] = 'storage/product/' . $image_name;
        }
        $product->update($input);
        return responseSuccess($product,'request success', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }

    public function getHomeProducts()
    {
        $data['bestSeller'] = Product::orderBy('sold', 'desc')->limit(5)->get();
        $data['sale'] = Product::where('sale', 1)->get();
        return responseSuccess($data, "Request success", 200);
    }

    public function sale()
    {
        return Product::where('sale', 1)->get();
    }
}
