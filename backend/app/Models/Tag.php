<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * カテゴリ生成
     *
     * @param  string $tag
     * @return \App\Models\Tag
     */
    public function findTagOrCreate(string $tag): Tag
    {
        return Tag::firstOrCreate(['tag_name' => $tag]);
    }

    /**
     * カテゴリ検索
     *
     * @param  int $id
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function findCategoryById(int $id): LengthAwarePaginator
    {
        return Tag::find($id)->posts()->where('is_published', 1)->latest()->paginate(9);
    }

    /**
     * カテゴリ取得
     *
     * @param  void
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function findCategory(): Collection
    {
        return Tag::take(10)->latest()->get();
    }
}
