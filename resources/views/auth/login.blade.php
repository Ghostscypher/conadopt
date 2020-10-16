@section('page-title', 'Login')

@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/login.css')}}">
@stop

@section('content')
    <div class="nav">
        <ul>
            <li><a href="{{url('/')}}">Home</a></li>
            <li><a href="{{url('register')}}">Register</a></li>
            <li><a href="{{url('contact_us')}}">Contact Us</a></li>
        </ul>
    </div>
    <div class="gallery">
        <div class="title">OAS Login</div>
        <form action="#" method="POST" onsubmit="return false">
            <div class="input-wrap">
                <div class="input-row">
                    <label for="username">Username :</label>
                    <div class="input-n-error">
                        <input id="username" type="text" placeholder="Enter Username" required>
                        <div id="error-username" class="error"></div>
                    </div>
                </div>

                <div class="input-row">
                    <label for="password">Password :</label>
                    <div class="input-n-error">
                        <input id="password" type="password" placeholder="Enter Password" required>
                        <div id="error-password" class="error"></div>
                    </div>
                </div>
            </div>
            <div class="buttons">
                <button id="btn-login">Login</button>
                <button><a id="a-tag-block" href="{{url('register')}}">Sign Up</a></button>
            </div>
        </form>
    </div>
    <div class="footer">
        Copyright Conrad OAS 2020
    </div>
@endsection

@include('layout.body')

<script>
    qs('#btn-login').onclick = function(){

        qsa('.error').forEach(element => {
            element.innerHTML = '';
        });

        ajaxPOST(`${location.origin}/login`, JSON.stringify({
            username: qs('#username').value,
            password: qs('#password').value,
        }), null, onLoginCallBack);

        return false;
    };

    function onLoginCallBack(data){

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
