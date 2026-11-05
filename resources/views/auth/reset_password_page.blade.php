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
            <form  action="{{route('reset.password.post')}}" method="POST" id="forgotpassword">
                @csrf
                <div class="mb-5">
                    <label class="label block mb-2" for="email">Email</label>
                    <input id="email" type="text" class="form-control bg-gray-100" value="{{$email}}" name="email" disabled>
                </div>
                <input type="hidden" name="email" value="{{$email}}">
                <input type="hidden" name="token" value="{{ $token }}">
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
                <div class="mb-3">
                    <label for="tuvConfirm_password" class="form-label">Confirm Password:</label>
                    <input id="tuvConfirm_password" type="password" placeholder="Enter Confirm Password"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        name="password_confirmation" required>
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="flex items-center">
                    <button type="submit" class="btn text-center custom-login-small-button">Change</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <script src="{{asset('js/bootstrap-validation.js')}}"></script>
@endsection

