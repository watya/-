<!-- 詳細画面 -->
@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/show.css') }}">
<link rel="stylesheet" href="{{ asset('css/markdown.css') }}">

<div class="container">

    <body id="top">

        <div class="card-body">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title1">{{ $post->title }}</h5>

                    <h5 class="card-title2">
                        {{ $post->created_at }}
                    </h5>

                    <h5 class="card-title3">
                        @foreach($post->tags as $tag)
                        <a href="{{ route('posts.category', $tag->id) }}">
                            #{{ $tag->tag_name }}
                        </a>
                        @endforeach
                    </h5>

                    <div class="markdown-body .pl-k">
                        <p class="card-text1">@include('markdowns.content')</p>
                    </div>

                </div>

            </div>

            <ul class="navbar-nav ml-auto">
                @guest

                @else
                <div class="edit">
                    <a href="{{ route('posts.edit',$post->id) }}" class="btn btn-primary">編集</a>

                    <form method="POST" action="{{ route('posts.destroy',$post->id) }}" onSubmit="return checkDelete()">
                        @csrf
                        @method('DELETE')
                        <div class="red">
                            <button type="submit" class="btn btn-primary" onclick=>
                                削除
                            </button>
                            <div>
                    </form>

                </div>
                @endguest
            </ul>

            <div class="p-3">

                <h3 class="card-title">コメント一覧</h3>
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

                <div class="blue">
                    <a href="{{ route('comments.create', ['post_id' => $post->id]) }}" class="btn btn-primary">コメントする</a>
                </div>
            </div>

            <div class="green">
                <a href="{{ route('posts.index') }}">ブログ一覧へ</a>
            </div>

            <div class="greeen">
                <a href="#top" id="btn">ページTOPへ戻る</a>
            </div>

    </body>
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