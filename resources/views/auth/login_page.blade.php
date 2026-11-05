@extends('layout')
@section('title', 'Login')
@section('content')
<div class="d-flex flex-column col-12 min-vh-100 justify-content-center align-items-center text-center">

    <div class="card w-100 shadow m-4 text-center m-2" style="max-width: 540px; background-color:#ffffff;">
        <div class="card-body">
            <img class="mb-4" src="{{ URL::asset('/images/ilab_logo_empty.png')}}" alt="TUV-Labs" style="width: 150px">
            <h2 class="text" style="color: #111dc7">Login</h2>
            @if (Session::has('success_message'))
                <div class="alert alert-success text-center mt-4" role="alert">
                    {{ Session::get('success_message') }}
                </div>
            @endif
            @if (Session::has('message'))
                <div class="alert alert-danger text-center mt-4" role="alert">
                    {{ Session::get('message') }}
                </div>
            @endif
            <form method="POST" action="{{ route('user-login') }}" class="needs-validation mt-4 pt-2" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="tuvEmailAddress" class="form-label">Email Address:</label>
                    <input id="tuvEmailAddress" type="email" placeholder="Enter Your Email address"
                        class="form-control @error('email') is-invalid @enderror" name="email" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tuvPassword" class="form-label">Password:</label>
                    <input id="tuvPassword" type="password" placeholder="Enter Password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="row m-4">
                    <div class="col-8">
                        <div class="form-check text-center" style="max-width: 140px;">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-4">
                        <a href="{{ url('forgot-password') }}" class="card-link text" style="color: #111dc7">Forgot Password</a>
                    </div>
                </div>
                
                <button type="submit" class="btn text-center custom-login-small-button">Login</button>
            </form>
            <!--
                <div class="mt-4">
                    Don't have an account?  <a href="{{ url('register') }}" class="card-link text-light">Register</a>
                </div>
            -->
        </div>
    </div>
</div>
    <script src="{{asset('js/bootstrap-validation.js')}}"></script>
@endsection

