<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Image;
use Log;
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
     * @return \Illuminate\View\View;
     */
    public function index(Request $request): View
    {
        $posts = $this->Post->findPublishPost();
        $categories = $this->Tag->findCategory();

        return view(
            'posts.index',
            [
                'posts' => $posts,
                'categories' => $categories,
            ]
        );
    }

    /**
     * ブログ作成画面
     *
     * @return \Illuminate\View\View;
     */
    public function create(): View
    {
        return view('posts.create', []);
    }

    /**
     * ブログ投稿
     *
     * @param  \Illuminate\Http\Request $request
     * @param int[] $tag_ids
     * @return void
     */
    public function store(PostRequest $request): void
    {
        \DB::beginTransaction();

        preg_match_all('/([a-zA-Z0-9０-９ぁ-んァ-ヶー一-龠]+)/u', $request->tagCategory, $match);;

        $tags = [];
        foreach ($match[1] as $tag) {
            $found = $this->Tag->findTagOrCreate($tag);
            array_push($tags, $found);
        }

        $tag_ids = [];
        foreach ($tags as $tag) {
            array_push($tag_ids, $tag['id']);
        }

        $post = $this->Post->savePost($request, $tag_ids);

        if ($request->thumbnail !== null) {
            $this->Image->saveImage($request, $post);
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
     * @return \Illuminate\View\View;
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
     * @return \Illuminate\View\View;
     */
    public function edit(int $id): View
    {
        $post = $this->Post->findPostById($id);

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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param int[] $tag_ids
     * @return void
     */

    public function update(PostRequest $request, int $id): void
    {
        \DB::beginTransaction();

        $post = $this->Post->findPostById($id);

        $post->tags()->detach();

        preg_match_all('/([a-zA-Z0-9０-９ぁ-んァ-ヶー一-龠]+)/u', $request->tagCategory, $match);

        $tags = [];
        foreach ($match[1] as $tag) {
            $found = $this->Tag->findTagOrCreate($tag);
            array_push($tags, $found);
        }

        $tag_ids = [];
        foreach ($tags as $tag) {
            array_push($tag_ids, $tag['id']);
        }

        // $attributes = $request->only(['content', 'title', 'is_published','thumbnail']);
        // $post = $this->Post->updatePost($attributes, $tag_ids);
        $post->updatePost($request, $tag_ids);

        if ($request->thumbnail != null) {
            $this->Image->saveImage($request, $post);
        };

        \DB::commit();

        if ((int)$attributes['is_published'] === 1) {
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
        $search = $request->search;
        $posts = $this->Post->findPostByTitleOrContent($search);
        // $posts = $this->Post->findPostByTitleOrContent($request);

        $search_result = $request->search . 'の検索結果' . $posts->total() . '件';

        $categories = $this->Tag->findCategory();

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
     * @return \Illuminate\View\View;
     */
    public function category(int $id): View
    {
        $posts = $this->Tag->findCategoryById($id);
        $categories = $this->Tag->findCategory();

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
        $posts = $this->Post->findArchivePost();
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
     * @return \Illuminate\View\View;
     */
    public function month(Request $request): View
    {
        $year = $request->year;
        $month = $request->month;
        $start = "$year-$month-01";
        $end = "$year-$month-31";

        $posts = $this->Post->findPostByCreated($start, $end);
        $categories = $this->Tag->findCategory();
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
