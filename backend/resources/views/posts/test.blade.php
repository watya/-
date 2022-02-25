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

      <!-- <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" onSubmit="return checkSubmit()"> -->

        <div class="form-group" id="app">
          <markdown-component></markdown-component>
        </div>

      <!-- </form> -->
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>

<!-- 
<script type="application/javascript">
  function checkSubmit() {
    if (window.confirm('投稿してよろしいですか？')) {
      console.log(1);
    } else {
      console.log(0);
    }
  }
</script> -->

@endsection

