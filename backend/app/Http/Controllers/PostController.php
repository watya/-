<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Image;
use Illuminate\Http\RedirectResponse;


class PostController extends Controller
{

    /** @var Post $Post */
    private $Post;

    /** @var Image $Image */
    private $Image;

    /** @var Tag $Tag */
    private $Tag;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->Post = new Post();
        $this->Image = new Image();
        $this->Tag = new Tag();
    }

    /**
     * ブログトップページ
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $posts = $this->Post->findPublish();
        $categories = $this->Tag->findPopular();

        // for ($i = 1; $i <= 12; $i++) {
        //     $counts[] = Post::where('is_published', 1)->whereBetween('created_at', ["2022-$i-01", "2022-$i-31"])->count();
        // }

        for ($i = 1; $i <= 12; $i++) {
            $counts[] = Post::where('is_published', 1)->whereYear('created_at',  2022)->whereMonth('created_at', $i)
                ->get();
        }


        return view(
            'posts.index',
            [
                'posts' => $posts,
                'categories' => $categories,
                'counts' => $counts,
            ]
        );
    }

    /**
     * ブログ作成画面
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('posts.create', []);
    }

    /**
     * ブログ投稿
     *
     * @param  \App\Http\Requests\PostRequest $request
     * @param int[] $tag_ids
     * @return void
     */
    public function store(PostRequest $request): void
    {
        \DB::beginTransaction();

        preg_match_all('/([a-zA-Z0-9０-９ぁ-んァ-ヶー一-龠]+)/u', $request->tagCategory, $match);;

        $tags = [];
        foreach ($match[1] as $tag) {
            $found = $this->Tag->findOrCreate($tag);
            array_push($tags, $found);
        }

        $tag_ids = [];
        foreach ($tags as $tag) {
            array_push($tag_ids, $tag['id']);
        }

        $attributes = $request->only(['content', 'title', 'is_published']);
        $post = $this->Post->savePost($attributes, $tag_ids);

        if ($request->thumbnail !== null) {
            $this->Image->saveImage($request->only('thumbnail'), $post);
        };
        \DB::commit();

        if ((int)$post->is_published === 1) {
            \Session::flash('err_msg', 'ブログを投稿しました',);
        } else {
            \Session::flash('err_msg', 'ブログを下書きに保存しました');
        }
    }

    /**
     * ブログ詳細画面
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show(Post $post): View
    {
        $post->load('user', 'comments.user');
        $user_id = \Auth::id();

        return view(
            'posts.show',
            [
                'post' => $post,
                'user_id' => $user_id,
            ]
        );
    }

    /**
     * ブログ編集画面
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id): View
    {
        $post = $this->Post->findById($id);

        return view(
            'posts.edit',
            [
                'post' => $post
            ]
        );
    }

    /**
     * ブログ更新
     *
     * @param  \App\Http\Requests\PostRequest $request
     * @param  int  $id
     * @param int[] $tag_ids
     * @return void
     */

    public function update(PostRequest $request, int $id): void
    {
        \DB::beginTransaction();

        $post = $this->Post->findById($id);

        $post->tags()->detach();

        preg_match_all('/([a-zA-Z0-9０-９ぁ-んァ-ヶー一-龠]+)/u', $request->tagCategory, $match);

        $tags = [];
        foreach ($match[1] as $tag) {
            $found = $this->Tag->findOrCreate($tag);
            array_push($tags, $found);
        }

        $tag_ids = [];
        foreach ($tags as $tag) {
            array_push($tag_ids, $tag['id']);
        }

        $attributes = $request->only(['content', 'title', 'is_published']);
        $post->updatePost($attributes, $tag_ids);

        if ($request->thumbnail !== null) {
            $this->Image->saveImage($request->only('thumbnail'), $post);
        };

        \DB::commit();

        if ((int)$post->is_published === 1) {
            \Session::flash('err_msg', 'ブログを更新しました');
        } else {
            \Session::flash('err_msg', 'ブログを下書きに保存しました');
        }
    }

    /**
     * ブログ削除
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        if (empty($id)) {
            \Session::flash('err_msg', 'データがありません');
            return redirect('/');
        }

        try {
            Post::destroyPost($id);
        } catch (\Throwable $e) {
            abort(500);
        }

        \Session::flash('err_msg', 'ブログを削除しました');
        return redirect('/');
    }

    /**
     * ブログ検索
     * @param  Request  $request
     * @return \Illuminate\View\View;
     */
    public function search(Request $request): View
    {
        $posts = $this->Post->findByTitleOrContent($request->only('search'));

        $search_result = $request->search . 'の検索結果' . $posts->total() . '件';

        $categories = $this->Tag->findPopular();

        return view(
            'posts.index',
            [
                'posts' => $posts,
                'search_result' => $search_result,
                'search_query' => $request->search,
                'categories' => $categories
            ]
        );
    }

    /**
     * カテゴリ一覧
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function category(int $id): View
    {
        $posts = $this->Tag->findById($id);
        $categories = $this->Tag->findPopular();

        return view(
            'posts.index',
            [
                'posts' => $posts,
                'categories' => $categories
            ]
        );
    }

    /**
     * 下書き一覧
     *
     * @param  Request  $request
     * @return \Illuminate\View\View;
     */
    public function archive(Request $request): View
    {
        $posts = $this->Post->findArchive();
        $user_id = \Auth::id();

        return view(
            'posts.publish',
            [
                'posts' => $posts,
                'user_id' => $user_id,
            ]
        );
    }

    /**
     * 月別一覧
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function month(Request $request): View
    {
        $year = $request->year;
        $month = $request->month;

        $posts = $this->Post->findByCreated($year, $month);

        $categories = app()->make(Tag::class)->findPopular();

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
