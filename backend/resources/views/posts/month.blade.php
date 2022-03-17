<!-- トップページ -->
<!-- トップページ -->
@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/month.css') }}">

<div class="index">
    <!-- <div class="row">
            <div class="col-md-16"> -->
    <div class="card-body-index">
        <div id="blog-top">{{$year}}年{{$month}}月の記事一覧</div>
        @if (session('err_msg'))
        <p class="text-danger">
            {{ session('err_msg') }}
        </p>
        @endif

        @isset($search_result)
        <!-- この中に値があれば表示 -->
        <h5 class="card-title">{{ $search_result }}</h5>
        @endisset

        <div class="card-body">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif

            @foreach ($posts as $post)
            <div class="card">
                <div class="card-body" id="card-body-content">

                    <div>
                        <div class="card-created">
                            {{ $post->created_at }}
                        </div>

                        <div class="card-title" id="post-title">
                            <a class="a-title" href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
                        </div>
                    </div>

                    <div class="card-image-tag">
                        <div class="card-image">
                            @if($post->images->isNotEmpty())
                            @foreach ($post->images as $image)
                            <a href="{{ route('posts.show', $post->id) }}">
                                <image id="image" src="{{ asset('storage/image/' . $image->image) }}">
                            </a>
                            @break
                            @endforeach
                            @else
                            <a href="{{ route('posts.show', $post->id) }}">
                                <image id="image"
                                    src="https://www.shoshinsha-design.com/wp-content/uploads/2020/05/noimage-760x460.png">
                            </a>
                            @endif
                        </div>
                        <div class="card-tag">
                            @foreach ($post->tags as $tag)
                            <a href="{{ route('posts.category', $tag->id) }}">
                                #{{ $tag->tag_name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if (isset($tag_name))
        {{ $posts->appends(['tag_name' => $tag_name])->links('pagination::bootstrap-4') }}
        @elseif(isset($search_query))
        {{ $posts->appends(['search' => $search_query])->links('pagination::bootstrap-4') }}
        @else
        {{ $posts->links('pagination::bootstrap-4') }}
        @endif

        <a href="{{ route('posts.index') }}">ブログ一覧へ</a>
        <a href="#app" id="btn">ページTOPへ戻る</a>

        <div class="categories">
            <h1>カテゴリ</h1>
            @foreach ($categories as $category)
            <div class="card-tag">
                <a href="{{ route('posts.category', $category->id) }}">
                    #{{ $category->tag_name }}
                </a>
            </div>
            @endforeach
        </div>

        <div class="month">
            <h1>月別記事</h1>
            <button class="button">2022</button>
            <div class="card-month">
                @for ($i = 1; $i <= 12; $i++) <div>
                    <a href="{{ route('posts.month', [2022,$i]) }}">
                        2022/{{ $i }}
                    </a>
            </div>
            @endfor
        </div>
    </div>
</div>

<!-- </div>
    </div> -->
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script>
    $(document).ready(function(){

    $('.card-month').hide();

    $('.button').click(function () {

         //toggleClassでshowのclassを追加または削除
        $('.month').toggleClass('show');

        //#appがshowのclassをを持っていれば
        if($('.month').hasClass('show')){
            $('.card-month').show();
        }else{
            $('.card-month').hide();
        }
    });
});
</script>