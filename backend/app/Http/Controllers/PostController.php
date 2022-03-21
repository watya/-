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
     * ブログトップページ
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        // DBから情報を取得して変数に値を入れている部分はモデルにメソッド作って返り値をここで変数に入れる
        /**
         * $post = new Post();
         * $category = new Tag();
         *
         * $posts = $post->findPublishPost();
         *
         * Postモデルに書くメソッド
         * public function findPublishPost()
         * {
         *    $posts = Post::where('is_published', 1)->latest()->paginate(9);
         *    $posts->load('user', 'tags', 'images');
         *    return $posts;
         * }
         *
         * $categories = $category->findCategory();
         * Tagモデルに書く
         * public function findCategory()
         * {
         *    return Tag::take(10)->latest()->get();
         * }
         */
        $posts = Post::where('is_published', 1)->latest()->paginate(9);
        $posts->load('user', 'tags', 'images');
        $categories = Tag::take(10)->latest()->get();

        return view(
            'posts.index',
            [
                'posts' => $posts, 'categories' => $categories
            ]
        );
        // 改行した方がいいかも
        // return view(
        //     'posts.index',
        //     [
        //         'posts' => $posts,
        //         'categories' => $categories
        //     ]
        // );
    }

    /**
     * ブログ作成画面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create', []);
    }

    /**
     * ブログ投稿
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        // 最初にタグを作る→そのあとポストをセーブする流れに
        \DB::beginTransaction();

        $post = new Post;
        $post->user_id = \Auth::id();
        $post->content = $request->content;
        $post->title = $request->title;
        $post->is_published = $request->is_published;

        // tagcategoryからtagを抽出。それを$matchに移行
        preg_match_all('/([a-zA-Z0-9０-９ぁ-んァ-ヶー一-龠]+)/u', $request->tagCategory, $match);;
        $tags = [];

        foreach ($match[1] as $tag) {
            // ここもモデルに書くので
            /**
             * ここで書くこと
             * $tag = new Tag();
             * $found = $tag->findTagOrCreate($tag);
             *
             * Tagモデル
             * public function findTagOrCreate(array $tag)
             * {
             *    return Tag::firstOrCreate(['tag_name' => $tag])
             * }
             */
            $found = Tag::firstOrCreate(['tag_name' => $tag]); //タグが既に存在していたら作らない。存在してなかったら作る。

            array_push($tags, $found);
        }

        $tag_ids = [];

        foreach ($tags as $tag) {
            array_push($tag_ids, $tag['id']);
        }

        /**
         * Postモデルでかくこと
         * public function savePost(): void
         * {
         *   $post = new Post;
         *   $post->user_id = \Auth::id();
         *   $post->content = $request->content;
         *   $post->title = $request->title;
         *   $post->is_published = $request->is_published;
         *
         *   $post->save();
         *   $post->tags()->attach($tag_ids);
         * }
         *
         * ここのコントローラでは
         * $post->savePost();
         */
        $post->save();
        $post->tags()->attach($tag_ids);

        if ($request->thumbnail != null) {
            $image = new Image;
            $image->image = $request->thumbnail;
            $image->post_id = $post->id;
            $image->save();
        };

        \DB::commit();

        // === 厳密比較すること
        if ($post->is_published == 1) {
            \Session::flash('err_msg', 'ブログを投稿しました');
        } else {
            \Session::flash('err_msg', 'ブログを下書きに保存しました');
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
        $user_id = \Auth::id();

        return view('posts.show', ['post' => $post, 'user_id' => $user_id,]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // ここもモデルに書く
        /**
         * public finction findPostById()
         */
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
        // storeと同じように切り分ける
        \DB::beginTransaction();

        $post = Post::find($id);

        $post->fill([
            'title' => $request->title,
            'content' => $request->content,
            'is_published' => $request->is_published,
        ]);

        $post->tags()->detach();

        // contentからtagを抽出。それを$matchに移行
        preg_match_all('/([a-zA-Z0-90-９ぁ-んァ-ヶー一-龠]+)/u', $request->tagCategory, $match);;

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

        if ($request->thumbnail != null) {
            $image = new Image;
            $image->image = $request->thumbnail;
            $image->post_id = $post->id;
            $image->save();
        }

        \DB::commit();

        if ($post->is_published == 1) {
            \Session::flash('err_msg', 'ブログを更新しました');
        } else {
            \Session::flash('err_msg', 'ブログを下書きに保存しました');
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
        if (empty($id)) {
            \Session::flash('err_msg', 'データがありません');
            return redirect('/');
        }

        try {
            // ここもモデルで書く
            /**
             * public function destroyPost(): void
             */
            Post::destroy($id);
        } catch (\Throwable $e) {
            abort(500);
        }

        \Session::flash('err_msg', 'ブログを削除しました');
        return redirect('/');
    }

    public function search(Request $request)
    {
        /**
         * モデルに書いて返り値を変数に入れる
         * public function findPostByTitleOrContent(): Post
         */
        $posts = Post::where('is_published', 1)->where(function ($query) use ($request) {
            $query->where('title', 'like', "%$request->search%")
                ->orWhere('content', 'like', "%$request->search%");
        })->paginate(9);

        $search_result = $request->search . 'の検索結果' . $posts->total() . '件';

        /**
         * public function findCategory(): Category
         */
        $categories = Tag::take(10)->latest()->get();

        return view('posts.index', ['posts' => $posts, 'search_result' => $search_result, 'search_query' => $request->search, 'categories' => $categories]);
        // 改行しよう
        // return view('posts.index', [
        //     'posts' => $posts,
        //     'search_result' => $search_result,
        //     'search_query' => $request->search,
        //     'categories' => $categories
        // ]);
    }

    public function category(int $id)
    {
        /**
         * Tagモデルに書く
         * public function findCategoryById();
         */
        $posts = Tag::find($id)->posts()->where('is_published', 1)->latest()->paginate(9);

        /**
         * findCategory()
         */
        $categories = Tag::take(10)->latest()->get();

        return view(
            'posts.index',
            [
                'posts' => $posts, 'categories' => $categories
            ]
        );
        // return view(
        //     'posts.index',
        //     [
        //         'posts' => $posts,
        //         'categories' => $categories
        //     ]
        // );
    }

    public function publish(Request $request)
    {
        /**
         * public function findArchivePost();
         */
        $posts = Post::where('is_published', 0)->latest()->paginate(5);
        $posts->load('user', 'tags', 'images');

        // 使ってない
        $id = $request->post_id;
        // 使ってない
        $image = Image::find($id);
        $user_id = \Auth::id();

        return view(
            'posts.publish',
            [
                'posts' => $posts,
                'user_id' => $user_id,
            ]
        );
    }

    public function month(Request $request)
    {
        $year = $request->year;
        $month = $request->month;

        $start = "$year-$month-01";
        $end = "$year-$month-31";
        /**
         * モデル書く
         * public finction findPostByCreated(string $start, string $end);
         */
        $posts = Post::where('is_published', 1)->whereBetween('created_at', [$start, $end])->latest()->paginate(9);
        $posts->load('user', 'tags', 'images');

        $categories = Tag::take(10)->latest()->get();

        $user_id = \Auth::id();

        return view(
            'posts.month',
            [
                'posts' => $posts,
                'user_id' => $user_id,
                'categories' => $categories,
                'year' => $year,
                'month' => $month,
            ]
        );
    }
}
