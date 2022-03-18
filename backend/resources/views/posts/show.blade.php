<!-- 詳細画面 -->
@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/show.css') }}">
<link rel="stylesheet" href="{{ asset('css/markdown.css') }}">

<div class="container">
    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="card-created">
                    {{ $post->created_at }}
                </div>

                <div class="post-title">{{ $post->title }}</div>

                <div class="card-title3">
                    @foreach($post->tags as $tag)
                    <a href="{{ route('posts.category', $tag->id) }}">
                        #{{ $tag->tag_name }}
                    </a>
                    @endforeach
                </div>

                <div class="markdown-body .pl-k">
                    <p class="card-text1">@include('markdowns.content')</p>
                </div>
            </div>
        </div>

        @if ($user_id == $post->user_id)
        <div class="edit">
            <form method="POST" action="{{ route('posts.destroy',$post->id) }}" onSubmit="return checkDelete()">
                @csrf
                @method('DELETE')
                <a href="{{ route('posts.edit',$post->id) }}" class="btn btn-outline-primary">編集</a>
                <button type="submit" class="btn btn-outline-danger" onclick=>
                    削除
                </button>
            </form>
        </div>
        @endif

        <div class="comments">
            <div class="comment">コメント一覧</div>
            @foreach($post->comments as $comment)
            <div class="card">
                <div class="card-body">
                    <p class="card-text">{{ $comment->comment }}</p>
                    <p class="card-text">
                        投稿者:<a href="{{ route('users.show', $comment->user->id) }}">{{ $comment->user->name }}</a>
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="blue">
            <a href="{{ route('comments.create', ['post_id' => $post->id]) }}" class="btn btn-info">コメントする</a>
        </div>

        <div class="to-top">
            <a href="{{ route('posts.index') }}" class="a-top">ブログ一覧へ</a>
            <a href="#app" id="btn" class="a-top">ページTOPへ戻る</a>
        </div>
    </div>
</div>

<script type="application/javascript">
    function checkDelete() {
        if (window.confirm('削除してよろしいですか？')) {
            return true;
        } else {
            return false;
        }
    }
</script>

</div>
@endsection