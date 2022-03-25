<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use Illuminate\Http\Request;

class Image extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'post_id', 'image'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * ファイル保存
     *
     * @param  string[] $thumbnail
     * @param  \App\Models\Post $post
     * @return void
     */
    public function saveImage(array $request, $post): void
    {
        $image = new Image;
        $image->image = $request['thumbnail'];
        $image->post_id = $post->id;
        $image->save();
    }

    public static function destroyImage(int $id): void
    {
        Image::destroy($id);
    }
}
