<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title') </title>

    <script src="{{asset('/js/axios.js')}}"></script>
    <script src="{{asset('/js/print.js')}}"></script>
    <script src="{{asset('/js/pure-ajax.js')}}"></script>
    @yield('css')

    <noscript>
        <style>
            #site-body {
                display: none;
            }
        </style>
    </noscript>
</head>
