<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Image;
use Log;
use Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $posts = Post::where('is_published', 1)->latest()->paginate(5);
        $posts->load('user', 'tags', 'images');

        $id = $request->post_id;
        $image = Image::find($id);

        return view(
            'posts.index',
            [
                'posts' => $posts
            ]
        );
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = new Post;
        $post->user_id = \Auth::id();
        $post->content = $request->content;
        $post->title = $request->title;
        $post->is_published = $request->is_published;

        // tagcategoryからtagを抽出。それを$matchに移行
        preg_match_all('/([a-zA-Z0-90-9ぁ-んァ-ヶー一-龠]+)/u', $request->tagCategory, $match);;

        $tags = [];

        foreach ($match[1] as $tag) {
            $found = Tag::firstOrCreate(['tag_name' => $tag]); //タグが既に存在していたら作らない。存在してなかったら作る。

            array_push($tags, $found);
        }

        $tag_ids = [];

        foreach ($tags as $tag) {
            array_push($tag_ids, $tag['id']);
        }

        $post->save();
        $post->tags()->attach($tag_ids);

        if ($request->imageData != null) {
            if ($request->imageData->isValid()){
                $image = new Image;
                $filename = $request->imageData->store('public/image');
                $image->image = basename($filename);
                $image->post_id = $post->id;
                $image->save();
            }
        }

        if ($post->is_published == 1) {
            \Session::flash('err_msg', 'ブログを投稿しました');
        } else {
            \Session::flash('err_msg', 'ブログをアーカイブに保存しました');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post->load('user', 'comments.user');

        return view('posts.show', ['post' => $post,]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(PostRequest $request, int $id)
    {
        //編集後のブログのデータを受け取る
        $inputs = $request->all();

        \DB::beginTransaction();

        //編集前のブログデータ
        $post = Post::find($inputs['id']);

        $post->fill([
            'title' => $inputs['title'],
            'content' => $inputs['content'],
            'is_published' => $inputs['is_published'],
        ]);

        $post->tags()->detach();

        // contentからtagを抽出。それを$matchに移行
        preg_match_all('/([a-zA-Z0-9０-９ぁ-んァ-ヶー一-龠]+)/u', $request->tagCategory, $match);;

        $tags = [];

        foreach ($match[1] as $tag) {
            $found = Tag::firstOrCreate(['tag_name' => $tag]); //タグが既に存在していたら作らない。存在してなかったら作る。

            array_push($tags, $found);
        }

        $tag_ids = [];

        foreach ($tags as $tag) {
            array_push($tag_ids, $tag['id']);
        }

        $post->save();
        $post->tags()->syncWithoutDetaching($tag_ids);

        if ($request->image != null) {
            if ($request->image->isValid()) {
                $image = new Image;
                $filename = $request->image->store('public/image');
                $image->image = basename($filename);
                $image->post_id = $post->id;
                $image->save();
            }
        }


        \DB::commit();

        if ($post->is_published == 1) {
            \Session::flash('err_msg', 'ブログを投稿しました');
        } else {
            \Session::flash('err_msg', 'ブログをアーカイブに保存しました');
        }
        return redirect('/');


        // \Session::flash('err_msg', 'ブログを更新しました');
        // return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (empty($id)) {
            \Session::flash('err_msg', 'データがありませんよ？');
            return redirect('/');
        }

        try {
            Post::destroy($id);
        } catch (\Throwable $e) {
            abort(500);
        }

        \Session::flash('err_msg', '削除しました');
        return redirect('/');
    }

    public function search(Request $request)
    {
        $posts = Post::where('title', 'like', "%$request->search%")->orwhere('content', 'like', "%$request->search%")
            ->paginate(5);

        $search_result = $request->search . 'の検索結果' . $posts->total() . '件';

        return view('posts.index', ['posts' => $posts, 'search_result' => $search_result, 'search_query' => $request->search]);
    }

    public function category(int $id)
    {
        $posts = Tag::find($id)->posts()->latest()->paginate(5);

        return view(
            'posts.index',
            [
                'posts' => $posts,
            ]
        );
    }

    public function publish(Request $request)
    {
        $posts = Post::where('is_published', 0)->latest()->paginate(5);
        $posts->load('user', 'tags', 'images');

        $id = $request->post_id;
        $image = Image::find($id);

        return view(
            'posts.publish',
            [
                'posts' => $posts
            ]
        );
    }

    public function test()
    {
        return view('posts.test', []);
    }

}
