@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">

<div class="card-header">編集</div>
<div class="card-body">
  @if (session('status'))
  <div class="alert alert-success" role="alert">
    {{ session('status') }}
  </div>
  @endif

  <div class="card">
    <div class="card-body" id="edit">
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
        <edit-component v-bind:post="{{ json_encode($post) }}" v-bind:tags="{{ ($post->tags) }}"
          v-bind:images="{{ ($post->images) }}"></edit-component>
      </div>
    </div>
  </div>
</div>

@endsection