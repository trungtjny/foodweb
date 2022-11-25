<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Category::query();
        $filter = $request->get('filter');
        if(!empty($filter['key'])) {
            $query = $query->where('name', 'like', '%'.$filter['key'].'%');
        }
        return $query->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRequest $request)
    {
       return Category::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $input=$request->all();
        if ($request->hasFile('thumb')) {
            logger("SSSSSSSSSSS");
            $image = $request->file('thumb');
            $type = $request->file('thumb')->extension();
            $image_name = time() . '-product.' . $type;
            $path = Storage::disk('local')->put('/public/category/' . $image_name, $image->getContent());
            $input['img'] = 'storage/category/' . $image_name;
        }
        return Category::create($input);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Category::findOrFail($id);
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
        $cate = Category::findOrFail($id);
        $input=$request->all();
        if ($request->hasFile('thumb')) {
            $image = $request->file('thumb');
            $type = $request->file('thumb')->extension();
            $image_name = time() . '-product.' . $type;
            $path = Storage::disk('local')->put('/public/category/' . $image_name, $image->getContent());
            $input['img'] = 'storage/category/' . $image_name;
        }
        $cate->update($input);
        return $cate;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        logger("XXXXXoa");
        $cate = Category::findOrFail($id);
        return $cate->delete();
    }

    public function getProduct($id)
    {
        $category = Category::where('id', $id)->with('products')->first();
        return response()->json($category['products']);
    }
}
