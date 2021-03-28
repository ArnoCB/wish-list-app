<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Goods and Shoes Shop</title>

        <!-- Favicons -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Arno: Removed defer for now, because JQuery was not yet active. -->
        <script src="{{ asset('js/app.js') }}"></script>
        @yield('head_scripts')

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @yield('styles')
    </head>

    <body class="antialiased">
        @include('layouts.navbar')

        @yield('content')
        @yield('script')
    </body>
</html>
