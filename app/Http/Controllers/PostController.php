<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $post = Post::query();
        $filter = $request->get('filter');
        if (!empty($filter['key'])) {
            $post = $post->where('title', 'like', '%' . $filter['key'] . '%')->orWhere('desc', 'like', '%' . $filter['key'] . '%');
        }
        // if(!empty($filter['sortby'])) {
        //     $post = $post->orderBy($filter['sortby'], $filter['sortop']);
        // }
        $post = $post->orderBy('id', 'desc')->get();

        return responseSuccess($post, "Request success.", 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $input = $request->input();
        if ($request->hasFile('thumb')) {
            $image = $request->file('file');
            $type = $request->file('file')->extension();
            $image_name = time() . '-product.' . $type;
            $path = Storage::disk('local')->put('/public/post/' . $image_name, $image->getContent());
            $input['thumb'] = 'storage/post/' . $image_name;
        }
        return Post::create($input);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        if ($post) return responseSuccess($post, "Request success", 200);
        else {
            return responseError(null, "Fail", 424);
        }
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
        $input = $request->input();
        if ($request->hasFile('thumb')) {
            $image = $request->file('file');
            $type = $request->file('file')->extension();
            $image_name = time() . '-product.' . $type;
            $path = Storage::disk('local')->put('/public/post/' . $image_name, $image->getContent());
            $input['thumb'] = 'storage/post/' . $image_name;
        }
        return Post::findOrFail($id)->update($input);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = Post::findOrFail($id)->delete();
        return responseSuccess($status,"Request Success", 200);
    }
}
