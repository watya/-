<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;

class Post extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'user_id', 'category_id', 'content', 'title', 'image', 'is_published'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMAny(Comment::class, 'post_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag')->withTimestamps();
    }

    public function images()
    {
        return $this->hasMAny(Image::class);
    }

    /**
     * 公開記事取得
     *
     * @param  void
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function findPublishPost(): LengthAwarePaginator
    {
        $posts = Post::where('is_published', 1)->latest()->paginate(9);
        $posts->load('user', 'tags', 'images');
        return $posts;
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

    /**
     * ブログ保存
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int[] $tag_ids
     * @return \App\Models\Post
     */
    public function savePost($request, array $tag_ids): Post
    {
        $post = new Post();
        $this->user_id = \Auth::id();
        $this->content = $request->content;
        $this->title = $request->title;
        $this->is_published = $request->is_published;

        $this->save();
        $this->tags()->attach($tag_ids);
        return $this;
    }

    /**
     * 編集ブログ取得
     *
     * @param  int $id
     * @return \App\Models\Post
     */
    public function findPostById(int $id): Post
    {
        return Post::find($id);
    }

    /**
     * ブログ更新
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int[] $tag_ids
     * @return void
     */
    public function updatePost($request, array $tag_ids): void
    {
        $this->fill([
            'title' => $request->title,
            'content' => $request->content,
            'is_published' => $request->is_published,
        ]);

        $this->save();
        $this->tags()->attach($tag_ids);
    }

    /**
     * ブログ削除
     *
     * @param  int $id
     * @return void
     */
    public static function destroyPost(int $id): void
    {
        Post::destroy($id);
    }

    /**
     * ブログ検索(タイトルor本文)
     *
     * @param  \Illuminate\Http\Request $request
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function findPostByTitleOrContent($request): LengthAwarePaginator
    {
        return $posts = Post::where('is_published', 1)->where(function ($query) use ($request) {
            $query->where('title', 'like', "%$request->search%")
                ->orWhere('content', 'like', "%$request->search%");
        })->paginate(9);
    }


    /**
     * 非公開ブログ取得
     *
     * @param  void
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function findArchivePost(): LengthAwarePaginator
    {
        $posts = Post::where('is_published', 0)->latest()->paginate(5);
        $posts->load('user', 'tags', 'images');
        return $posts;
    }

    /**
     * 日付別ブログ取得
     *
     * @param  string $start
     * @param  string $end
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function findPostByCreated(string $start, string $end): LengthAwarePaginator
    {
        $posts = Post::where('is_published', 1)->whereBetween('created_at', [$start, $end])->latest()->paginate(9);
        $posts->load('user', 'tags', 'images');
        return $posts;
    }
}
