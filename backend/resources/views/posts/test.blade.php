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

        <div class="form-group">
          <markdown-component></markdown-component>
        </div>
    </div>
  </div>
</div>

@endsection

