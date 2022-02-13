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

        <div class="form-group" id="file-preview">
          <label for="exampleFormControlFile1">サムネイル</label>
          <input type="file" ref="file" class="form-control-file" id="exampleFormControlFile1" name="image" accept="image/*" v-on:change="onFileChange">
          <img class="userInfo__icon" v-bind:src="imageData" v-if="imageData" style="width: 270px;">
          <button class="btn btn-danger" v-if="imageData" @click="resetFile()">削除</button>
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

<!-- <script type="application/javascript">
  function checkSubmit() {
    if (window.confirm('投稿してよろしいですか？')) {
      return true;
    } else {
      return false;
    }
  }
</script> -->

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

<script>
  new Vue({
      el: '#file-preview',
      data: {
          imageData: '' //画像格納用変数
      },
      methods: {
        onFileChange(e) {
            const files = e.target.files;

            if(files.length > 0) {

                const file = files[0];
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.imageData = e.target.result;

                };
                reader.readAsDataURL(file);
            }
        },
        resetFile() {
          const input = this.$refs.file;
          input.type = 'text';
          input.type = 'file';
          this.imageData = '';
        }
      }
  });
</script>

@endsection