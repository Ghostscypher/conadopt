@section('page-title', 'Online Adoption System')

@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/home.css')}}">
@stop

@section('content')
    <div class="nav">
        <ul>
            <li><a href="#" class="active">Home</a></li>
        <li><a href="{{url('login')}}">Login</a></li>
            <li><a href="{{url('contact_us')}}">Contact Us</a></li>
        </ul>
    </div>
    <div class="gallery">
    </div>
    <div class="footer">
        Copyright Conrad OAS 2020
    </div>
@endsection

@include('layout.body')
