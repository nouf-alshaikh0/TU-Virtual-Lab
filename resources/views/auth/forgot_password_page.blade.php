@extends('layout')
@section('title', 'Forgot Password')
@section('content')
<div class="d-flex flex-column col-12 min-vh-100 justify-content-center align-items-center text-center">

    <div class="card w-100 shadow m-4 text-center m-2" style="max-width: 540px; background-color:#ffffff;">
        <div class="card-body">
            <img class="mb-4" src="{{ URL::asset('/images/ilab_logo_empty.png')}}" alt="TUV-Labs" style="width: 150px">
            <h2 class="text" style="color: #111dc7">Forgot Password</h2>
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
            <form method="POST" action="{{ route('forgot.password.link') }}" class="needs-validation mt-4 pt-2" novalidate>
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
                <div class="d-flex justify-content-between m-2">
                    <button type="submit" class="btn text-center custom-login-small-button">Send</button>
                    <a href="{{ url('login') }}" class="card-link text-light">Back to login page</a>
                </div>
                
                
            </form>
        </div>
    </div>
</div>
    <script src="{{asset('js/bootstrap-validation.js')}}"></script>
@endsection

