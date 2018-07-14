<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SodexoMash</title>

        <link rel="stylesheet" href="{{ mix_except_in_tests('/css/app.css') }}">
    </head>
    <body>
        @include('layouts.header')
        <div id="app">
                <h2 class='text-center'>Which meal do you prefer?</h2>
            @yield('content')
        </div>
        @include('layouts.footer')

        <script src="{{ mix_except_in_tests('/js/manifest.js') }}"></script>
        <script src="{{ mix_except_in_tests('/js/vendor.js') }}"></script>
        <script src="{{ mix_except_in_tests('/js/app.js') }}"></script>
        @yield('js')
    </body>
</html>
