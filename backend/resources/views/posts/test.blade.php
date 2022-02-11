@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://uicdn.toast.com/tui-editor/latest/tui-editor.css">
<link rel="stylesheet" href="https://uicdn.toast.com/tui-editor/latest/tui-editor-contents.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/codemirror.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/github.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">


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
      <!-- onSubmit="return checkSubmit()" -->
        {{ csrf_field() }}

        <div class="form-group">
          <p>タイトル</p>
          <input type="text" class="form-control" id="inputTitle" placeholder="title" name="title">
        </div>

        <div class="form-group">
          <p>カテゴリ</p>
          <input type="text" class="form-control" placeholder="category" name="tagCategory" id="inputCategory">
        </div>

        <div class="form-group">
          <p>サムネイル</p>
          <input type="file" class="form-control-file" id="exampleFormControlFile1" name="image" id="thumbnail">
        </div>
        <div id="file_viewer"></div>

        <p>本文</p>
        <div id="editSection"></div>

        <div class="form-group">
          <p>公開設定</p>
          <select input type="id" id="published" name="is_published">
            <option value="1">公開</option>
            <option value="0">非公開</option>
          </select>
        </div>

        <input type="hidden" name="user_id" value="{{ Auth::id() }}" id="userId">

        <button id="button1" class="btn btn-primary">投稿</button>
        <a href="{{ route('posts.index') }}" class="btn btn-primary">キャンセル</a>
    </div>
  </div>
</div>

<!-- <script type="application/javascript">
  function checkSubmit() {
    if (window.confirm('投稿してよろしいですか？')) {
      return true;
    } else {
      return false;
    }
  }
</script> -->


<script  type="application/javascript" src="https://uicdn.toast.com/tui-editor/latest/tui-editor-Editor-full.js"></script>
<!-- <script  type="application/javascript" src="{{ asset('js/app.js') }}"></script> -->

@endsection
