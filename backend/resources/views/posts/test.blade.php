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

        <div class="form-group" id="app">
          <axios-component></axios-component>
        </div>

        <div class="form-group" id="file-preview">
          <label for="exampleFormControlFile1">サムネイル</label>
          <input type="file" ref="file" class="form-control-file" id="exampleFormControlFile1" name="image" accept="image/*" v-on:change="onFileChange">
          <img class="userInfo__icon" v-bind:src="imageData" v-if="imageData" style="width: 270px;">
          <button class="btn btn-danger" v-if="imageData" @click="resetFile()">削除</button>
        </div>

        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

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

<script type="application/javascript">
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

