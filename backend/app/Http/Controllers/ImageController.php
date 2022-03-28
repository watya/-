<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{
    /**
     *ファイルアップロード
     *
     * @param  \App\Http\Requests\PostRequest $request;
     * @return string
     */
    public function store(PostRequest $request)
    {
        if ($request->imageData->isValid()) {
            $filename = $request->imageData->store('public/image');
            return ['thumbnail' => basename($filename)];
        }
    }

    /**
     * ファイル削除
     *
     * @param  int  $id
     * @return void
     *
     */
    public function destroy(int $id): void
    {
        Image::destroyImage($id);
    }
}
