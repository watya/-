<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<meta name=”viewport” content=”width=device-width, initial-scale=1”>

<head>
    <meta charset="utf-12">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TheWATABLOG') }}</title>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->
    <script src="https://kit.fontawesome.com/75b0e88a9a.js" crossorigin="anonymous"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link href="https://fonts.googleapis.com/css?family=Caveat" rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel='stylesheet'>

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container" id="app-container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'TheWATABLOG') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="card-body" id="search-area">
                    <form action="{{ route('posts.search') }}" method="get">
                        {{ csrf_field() }}
                        <div class="input-group">
                            <div class="form-outline">
                                <input type="text" class="form-control input-lg" placeholder="検索" name="search"
                                    value="">
                            </div>
                            <div class="search">
                                <button type="submit" class="btn btn-light">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links  ログインしてないときは～のやつ-->
                        <div>
                            @guest
                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('新規登録') }}</a>
                            </li>
                            @endif
                            @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('ログイン') }}</a>
                            </li>
                            @endif
                        </div>

                        @else

                        <!-- Authentication Links  ログインしているときは～のやつ-->
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a href="{{ route('posts.create') }}" class="dropdown-item">新規投稿</a>
                                <a href="{{ route('posts.archive') }}" class="dropdown-item">下書き一覧</a>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('ログアウト') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="content">
            @yield('content')
        </main>

    </div>
</body>

</html>