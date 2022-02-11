<!-- トップページ -->
@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/index.css') }}">

<div class="container">
    <div class="row">
        <div class="col-md-8">

            <div class="card-body">
                <div class="card-header">アーカイブ</div>

                @if(session('err_msg'))
                <p class="text-danger">
                    {{ session('err_msg') }}
                </p>
                @endif

                @isset($search_result)
                <!-- この中に値があれば表示 -->
                <h5 class="card-title">{{$search_result}}</h5>
                @endisset

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif


                    @foreach($posts as $post)

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }} </h5>

                            <h5 class="card-title">
                                投稿日{{ $post->created_at }}
                            </h5>

                            <h5 class="card-title">
                                @foreach($post->tags as $tag)
                                <a href="{{ route('posts.category', $tag->id) }}">
                                    #{{ $tag->tag_name }}
                                </a>
                                @endforeach
                            </h5>

                            <div class=d>
                                @isset($post->images)
                                @foreach($post->images as $image)
                                <image src="{{ asset('storage/image/'.$image->image) }}" width="300">
                                    @break
                                    @endforeach
                                    @endisset
                            </div>

                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">詳細へ</a>

                        </div>
                    </div>
                    @endforeach

                    <div class="green">
                        <a href="{{ route('posts.index') }}">ブログ一覧へ</a>
                    </div>

                    {{ $posts->links('pagination::bootstrap-4') }}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection