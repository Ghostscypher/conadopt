<!Doctype html>
<html lang="en">
@include('layout.header')

<body>
    <div  id="site-body" class="container">
        @yield('content')
    </div>
</body>

<noscript>
    <style>
        .noscript-class {
            width: 100vw;
            height: 100vh;
            text-align: center;
            margin: auto;
            font-size: 2em;
        }
    </style>
    
    <div class="noscript-class">Please enable javascript in order to continue</div>
</noscript>

@include('layout.footer')
</html>
