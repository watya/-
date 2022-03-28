@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">

<div class="card-header">新規投稿</div>
<div class="card-body">
  @if (session('status'))
  <div class="alert alert-success" role="alert">
    {{ session('status') }}
  </div>
  @endif

  <div class="card">
    <div class="card-body" id="create">
      @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <div>
        <create-component></create-component>
      </div>
    </div>
  </div>
</div>

@endsection