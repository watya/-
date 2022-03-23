<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Image;
use Illuminate\Http\RedirectResponse;

class ImageController extends Controller
{
    /**
     *ファイルアップロード
     *
     * @param  Request  $request
     * @return string
     *
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
