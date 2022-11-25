<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    public function index(Request $request)
    {
        $post = Post::query();
        $filter = $request->get('filter');
        if (!empty($filter['key'])) {
            $post = $post->where('title', 'like', '%' . $filter['key'] . '%')->orWhere('desc', 'like', '%' . $filter['key'] . '%');
        }
        $post = $post->orderBy('id', 'desc')->get();

        return $post;
    }
    public function sale(Request $request)
    {
        $post = Post::query();
        $filter = $request->get('filter');
        if (!empty($filter['key'])) {
            $post = $post->where('title', 'like', '%' . $filter['key'] . '%')->orWhere('desc', 'like', '%' . $filter['key'] . '%');
        }
        $post = $post->where('type', 1)->orderBy('id', 'desc')->get();

        return $post;
    }

    public function blog(Request $request)
    {
        $post = Post::query();
        $filter = $request->get('filter');
        if (!empty($filter['key'])) {
            $post = $post->where('title', 'like', '%' . $filter['key'] . '%')->orWhere('desc', 'like', '%' . $filter['key'] . '%');
        }
        $post = $post->where('type', 0)->orderBy('id', 'desc')->get();

        return $post;
    }
    public function store(PostRequest $request)
    {
        $input = $request->input();
        if ($request->hasFile('thumb')) {
            $image = $request->file('thumb');
            $type = $request->file('thumb')->extension();
            $image_name = time() . '-product.' . $type;
            $path = Storage::disk('local')->put('/public/post/' . $image_name, $image->getContent());
            $input['thumb'] = 'storage/post/' . $image_name;
        }
        return Post::create($input);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        if ($post) return $post;
        else {
            return responseError(null, "Fail", 424);
        }
    }

    public function update(Request $request, $id)
    {
        $input = $request->input();
        if ($request->hasFile('thumb')) {
            $image = $request->file('thumb');
            $type = $request->file('thumb')->extension();
            $image_name = time() . '-post.' . $type;
            $path = Storage::disk('local')->put('/public/post/' . $image_name, $image->getContent());
            $input['thumb'] = 'storage/post/' . $image_name;
        }
        $post = Post::findOrFail($id)->update($input);
        return $post;
    }

    public function destroy($id)
    {
        $status = Post::findOrFail($id)->delete();
        return response()->json("Request success");
    }
}
