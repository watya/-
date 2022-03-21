<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Image;

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

    public function findPublishPost()
    {
        $posts = Post::where('is_published', 1)->latest()->paginate(9);
        $posts->load('user', 'tags', 'images');
        return $posts;
    }

    public function findCategory()
    {
        return Tag::take(10)->latest()->get();
    }

    public function savePost($request, int $tag_ids)
    {
        $post = new Post();
        $post->user_id = \Auth::id();
        $post->content = $request->content;
        $post->title = $request->title;
        $post->is_published = $request->is_published;

        $post->save();
        $post->tags()->attach($tag_ids);
        return $post;
    }

    public function findPostById(int $id)
    {
        return Post::find($id);
    }

    public function updatePost($request, int $tag_ids): void
    {
        $this->fill([
            'title' => $request->title,
            'content' => $request->content,
            'is_published' => $request->is_published,
        ]);

        $this->save();
        $this->tags()->attach($tag_ids);
    }

    public static function destroyPost(int $id): void
    {
        Post::destroy($id);
    }

    public function findPostByTitleOrContent($request)
    {
        return $posts = Post::where('is_published', 1)->where(function ($query) use ($request) {
            $query->where('title', 'like', "%$request->search%")
                ->orWhere('content', 'like', "%$request->search%");
        })->paginate(9);
    }


    public function findArchivePost()
    {
        $posts = Post::where('is_published', 0)->latest()->paginate(5);
        $posts->load('user', 'tags', 'images');
        return $posts;
    }

    public function findPostByCreated(string $start, string $end){
        $posts = Post::where('is_published', 1)->whereBetween('created_at', [$start, $end])->latest()->paginate(9);
        $posts->load('user', 'tags', 'images');
        return $posts;
    }
}
