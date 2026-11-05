@extends('layout')
@section('title', 'Home')
@section('content')
<div class="row d-flex col-12 justify-content-between align-self-lg-center" style="margin-top: 100px">
    <div class="col-8 flex-fill m-4">
    </div>

</div>
<div class="container">
    <div class="row align-items-center">
        <div class="col text-left">
            <h1 class="fw-bolder" >Welcome</h1>
            <h1 class="fw-bolder" style="color: #27aae1">to <span class="text" style="color: #111dc7">iLab</span></h1>
            <p class="fs-5 lh-lg fw-medium m-4">
            A hub for future-forward thinkers. We're here to support your journey with dynamic resources and cutting-edge solutions, designed to help you thrive in today’s fast-paced digital world
            </p>
            @if(!auth()->user())
                <a href="{{url('login')}}" class="custom-login-button btn btn-lg w-50 fw-bolder">Login</a>
            @else
                <a href="{{url('dashboard')}}" class="custom-login-button btn btn-lg w-50 fw-bolder">Dashboard</a>
            @endif
        </div>
        <div class="col text-center">
    <img class="mb-4" src="{{ URL::asset('/images/ilab_logo_ar.png')}}" alt="TUV-Labs" style="width: 500px; height: auto;">
</div>

    </div>
</div>

@endsection
