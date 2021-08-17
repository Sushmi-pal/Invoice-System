<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
@include('layouts.shared.headSection')
    @yield('CssSection')
</head>
<body>
@include('layouts.shared.header')
@yield('body')
@yield('JsSection')
</body>
</html>
