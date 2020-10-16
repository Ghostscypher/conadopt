@section('page-title', 'Login')

@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/login.css')}}">
@stop

@section('content')
    <div class="nav">
        <ul>
            <li><a href="{{url('/')}}">Home</a></li>
            <li><a href="{{url('login')}}">Login</a></li>
            <li><a href="{{url('register')}}">Register</a></li>
        </ul>
    </div>
    <div class="gallery">
        <div class="title">OAS Contacts</div>
        <div class="contact-us-container">
            <div class="email">
                <div class="email-title">Email :</div>
                <div class="email-value">conradadoptionproject@gmail.com</div>
            </div>
            <div class="contact">
                <div class="contact-title">Contact :</div>
                <div class="contact-value">0749385934</div>
            </div>
            <div class="address">
                <div class="address-title">Address :</div>
                <div class="address-value">
                    <div class="address-content">PO BOX 43958,</div>
                    <div class="address-content">20100,</div>
                    <div class="address-content">MERU.</div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        Copyright Conrad OAS 2020
    </div>
@endsection

@include('layout.body')
