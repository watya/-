<!-- トップページ -->
@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-16">
            <div class="card-body">
                <div class="card-header">ブログ一覧</div>

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


                    @if(isset($tag_name))
                    {{ $posts->appends(['tag_name' => $tag_name])->links('pagination::bootstrap-4') }}

                    @elseif( isset($search_query) )
                    {{ $posts->appends(['search'=>$search_query])->links('pagination::bootstrap-4') }}

                    @else
                    {{ $posts->links('pagination::bootstrap-4') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection