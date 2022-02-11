@extends('layouts.app')

@section('content')

<div class="card-header">新規投稿</div>
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
      <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" onSubmit="return checkSubmit()">
        {{ csrf_field() }}

        <div class="form-group">
          <label for="exampleInputEmail1">タイトル</label>
          <input type="text" class="form-control" id="exampleInputEmail1" placeholder="title" name="title">
        </div>

        <div class="form-group">
          <label for="exampleInputEmail1">カテゴリ</label>
          <input type="text" class="form-control" placeholder="category" name="tagCategory">
        </div>

        <div class="form-group">
          <label for="exampleFormControlFile1">サムネイル</label>
          <input type="file" class="form-control-file" id="exampleFormControlFile1" name="image">
        </div>
        <div id="file_viewer"></div>


        <div class="form-group">
          <label for="comment">本文</label>
          <textarea class="form-control" rows="12" id="comment" name="content" cols="40"></textarea>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
          <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
          <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
          <script>
            $(document).ready(function() {
              var simplemde = new SimpleMDE({
                element: document.getElementById("comment"),
                spellChecker:false,
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

        <button type="submit" class="btn btn-primary">投稿</button>
        <a href="{{ route('posts.index') }}" class="btn btn-primary">キャンセル</a>
      </form>
    </div>
  </div>
</div>


<script type="application/javascript">
  function checkSubmit() {
    if (window.confirm('投稿してよろしいですか？')) {
      return true;
    } else {
      return false;
    }
  }
</script>

<script type="application/javascript" src="{{ asset('js/create.js') }}"></script>

@endsection