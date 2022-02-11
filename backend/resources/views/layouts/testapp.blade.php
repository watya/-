<!-- headタグ内で個別のCSS呼び出し -->
<head>
    @yield('css')
</head>

<!-- bodyタグ終了前に個別のJSファイルの呼び出し -->
<body>
    <!-- @yield('content') -->

    @yield('javascript-footer')
</body>