<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Tag extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'tag_name'
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }

    public function findTagOrCreate($tag)
    {
        return Tag::firstOrCreate(['tag_name' => $tag]);
    }

    public function findCategoryById($id)
    {
        return Tag::find($id)->posts()->where('is_published', 1)->latest()->paginate(9);
    }

    public function findCategory()
    {
        return Tag::take(10)->latest()->get();
    }
}
