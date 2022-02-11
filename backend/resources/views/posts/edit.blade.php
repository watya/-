@extends('layouts.app')

@section('content')
<div class="card-header">編集</div>
<div class="card-body">
  @if (session('status'))
  <div class="alert alert-success" role="alert">
    {{ session('status') }}
  </div>
  @endif

  <div class="card">
    <div class="card-body">
      @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
      <form action="{{ route('posts.update',$post->id) }}" method="POST" enctype="multipart/form-data" onSubmit="return checkSubmit()">
        @csrf
        @method('PUT')

        <input type="hidden" name="id" value="{{ $post->id }}">

        <div class="form-group">
          <label for="exampleInputEmail1">タイトル</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value='{{ $post->title }}' name="title">
        </div>

        <div class="form-group">
          <label for="exampleInputEmail1">カテゴリ</label>
          <input type="text" class="form-control" placeholder="category" name="tagCategory" value="@foreach($post->tags as $tag){{ $tag->tag_name }}  @endforeach">
        </div>


        <div class="form-group">
          <label for="exampleFormControlFile1">サムネイル</label>
          <input type="file" class="form-control-file" id="exampleFormControlFile1" name="image">
        </div>
        <div id="file_viewer"></div>


        <div class="form-group">
          <label for="comment">本文</label>
          <textarea class="form-control" rows="12" id="comment" name="content" cols="40">{{ $post->content }}</textarea>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
          <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
          <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
          <script>
            $(document).ready(function() {
              var simplemde = new SimpleMDE({
                element: document.getElementById("comment"),
              });
            });
          </script>
        </div>

        <div class="form-group">
          <label for="exampleFormControlSelect1">公開設定</label>
          <select input type="id" id="exampleFormControlSelect1" name="is_published">
            <option value="1">公開</option>
            <option value="0">非公開</option>
          </select>
        </div>


        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        <button type="submit" class="btn btn-primary">更新する</button>
        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">キャンセル</a>
      </form>
    </div>
  </div>

@isset($post->images)
@foreach($post->images as $image)
<image src="{{ asset('storage/image/'.$image->image) }}" width="150">
  <form action="{{ route('images.destroy',$image->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-primary">削除</button>
  </form>
@endforeach
@endisset

</div>

<script type="application/javascript">
  function checkSubmit() {
    if (window.confirm('更新してよろしいですか？')) {
      return true;
    } else {
      return false;
    }
  }
</script>

<script type="application/javascript" src="{{ asset('js/create.js') }}"></script>

@endsection