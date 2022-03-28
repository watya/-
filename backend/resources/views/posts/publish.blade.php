@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/publish.css') }}">

<div class="publish">
    <div class="card-body-publish">
        <div id="blog-top">下書き一覧</div>

        @if(session('err_msg'))
        <p class="text-danger">
            {{ session('err_msg') }}
        </p>
        @endif

        @isset($search_result)
        <h5 class="card-title">{{$search_result}}</h5>
        @endisset

        <div class="card-body">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif

            @foreach($posts as $post)
            @if ($user_id == $post->user_id)

            <div class="card">
                <div class="card-body">

                    <div class="card-created">
                        {{ $post->created_at }}
                    </div>

                    <div class="card-title" id="post-title">
                        <a class="a-title" href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
                    </div>

                    <div class="image">
                        @if($post->images->isNotEmpty())
                        @foreach($post->images as $image)
                        <image src="{{ asset('storage/image/'.$image->image) }}" width="300">
                            @break
                            @endforeach
                            @else
                            <a href="{{ route('posts.show', $post->id) }}">
                                <image id="image"
                                    src="https://www.shoshinsha-design.com/wp-content/uploads/2020/05/noimage-760x460.png"
                                    width="300">
                            </a>
                            @endif
                    </div>
                    <div class="card-title">
                        @foreach($post->tags as $tag)
                        <a href="{{ route('posts.category', $tag->id) }}">
                            #{{ $tag->tag_name }}
                        </a>
                        @endforeach
                    </div>

                    <a href="{{ route('posts.show', $post->id) }}" class="btn btn-secondary">詳細へ</a>

                </div>
            </div>
            @endif
            @endforeach

            <div class="to-top">
                <a href="{{ route('posts.index') }}" class="a-top">ブログ一覧へ</a>
                <a href="#app" id="btn" class="a-top">ページTOPへ戻る</a>
            </div>

            {{ $posts->links('pagination::bootstrap-4') }}

        </div>
    </div>
</div>

@endsection