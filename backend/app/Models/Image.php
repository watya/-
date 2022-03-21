<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

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
    public function saveImage($request, $post): void
    {
        $image = new Image;
        $image->image = $request->thumbnail;
        $image->post_id = $post->id;
        $image->save();
    }
}
