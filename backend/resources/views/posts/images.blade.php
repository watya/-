@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-20">
            <div class="card-body">
                <div class="card-header">画像一覧</div>
                @foreach($images as $image)
                <div class="card-body">
                    <image src="{{ asset('storage/image/'.$image->image) }}" width="380">
                        <a href="{{ route('posts.show', $image->post_id) }}" class="btn btn-primary">ブログ詳細へ</a>
                </div>
                @endforeach

                {{ $images->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection