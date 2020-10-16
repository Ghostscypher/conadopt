@section('page-title', 'Register')

@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/login.css')}}">
@stop

@section('content')
    <div class="nav">
        <ul>
            <li><a href="{{url('/')}}">Home</a></li>
            <li><a href="{{url('login')}}">Login</a></li>
            <li><a href="{{url('contact_us')}}">Contact Us</a></li>
        </ul>
    </div>
    <div class="gallery">
        <div class="title">OAS Register</div>
        <form action="#" method="POST" onsubmit="return false">
            <div class="input-wrap">
                <div class="input-row">
                    <label for="username">Username :</label>
                    <div class="input-n-error">
                        <input id="username" type="text" placeholder="Enter Username">
                        <div id="error-username" class="error"></div>
                    </div>
                </div>
                <div class="input-row">
                    <label for="password">Password :</label>
                    <div class="input-n-error">
                        <input id="password" type="password" placeholder="Enter Password">
                        <div id="error-password" class="error"></div>
                    </div>
                </div>
                <div class="input-row">
                    <label for="address">Address :</label>
                    <div class="input-n-error">
                        <input id="address" type="text" placeholder="Enter Address">
                        <div id="error-address" class="error"></div>
                    </div>
                </div>
                <div class="input-row">
                    <label for="email">Email :</label>
                    <div class="input-n-error">
                        <input id="email" type="text" placeholder="Enter Email">
                        <div id="error-email" class="error"></div>
                    </div>
                </div>
                <div class="input-row">
                    <label for="phone">Phone :</label>
                    <div class="input-n-error">
                        <input id="phone" type="text" placeholder="Enter Phone Number">
                        <div id="error-phone" class="error"></div>
                    </div>
                </div>
                <div class="input-row">
                    <label for="id_number">Id Number :</label>
                    <div class="input-n-error">
                        <input id="id_number" type="text" placeholder="Enter Id Number">
                        <div id="error-id_number" class="error"></div>
                    </div>
                </div>
            </div>
            <div class="buttons">
                <button id="btn-sign_up">Sign Up</button>
                <button><a id="a-tag-block" href="{{url('login')}}">Login</a></button>
            </div>
        </form>
    </div>
    <div class="footer">
        Copyright Conrad OAS 2020
    </div>
@endsection

@include('layout.body')

<script>
    qs('#btn-sign_up').onclick = function(){

        qsa('.error').forEach(element => {
            element.innerHTML = '';
        });

        ajaxPOST(`${location.origin}/register`, JSON.stringify({
            username: qs('#username').value,
            password: qs('#password').value,
            address: qs('#address').value,
            email: qs('#email').value,
            phone: qs('#phone').value,
            id_number: qs('#id_number').value,
        }), null, onRegisterCallback);

        return false;
    };

    function onRegisterCallback(data){

        if(data.code === 200 || data.code === 301){
            redirectTo(data.response.redirect, 1000);
        } else if(data.code === 400) {
            let messages = data.response.message;

            for (const message in messages) {
                qs(`#error-${message}`).innerHTML = messages[message];
            }

        }else if(result.code === 429){
            qs('#error-password').innerHTML = "Too many attempts please try again later";
        } else if(result.code === 419) {
            // Invalid csrf token refresh the page
            window.location.href = 'login';
        } else {
            qs('#error-password').innerHTML = 'An unknown error occured, refresh your page and try again';
        }
    }

</script>
