<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        return Slider::orderBy('id', 'desc')->limit(15)->get();
    }

    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $input = $request->all();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $type = $request->file('image')->extension();
            $image_name = time() . '-slider.' . $type;
            $path = Storage::disk('local')->put('/public/slider/' . $image_name, $image->getContent());
            $input['image'] = 'storage/slider/' . $image_name;
        }
        Slider::create($input);
    }

    public function show($id)
    {
        return Slider::findOrFail($id);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $type = $request->file('image')->extension();
            $image_name = time() . '-slider.' . $type;
            $path = Storage::disk('local')->put('/public/slider/' . $image_name, $image->getContent());
            $input['image'] = 'storage/slider/' . $image_name;
        }
        $slider = Slider::findOrFail($id)->update($input);
        return $slider;
    }

    public function destroy($id)
    {
        return Slider::findOrFail($id)->delete();
    }
}
