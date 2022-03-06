<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Image;

class ImageController extends Controller
{

    public function store(PostRequest $request)
    {
        if ($request->thumbnail->isValid()){
            $filename = $request->thumbnail->store('public/image');
            return ['thumbnail' => basename($filename)];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $image = Image::find($id);
        $post = Post::find($image->post_id);

        Image::destroy($id);
    }
}
