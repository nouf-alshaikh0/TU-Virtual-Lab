@extends('layout')
@section('title', 'Admin Darshboard')
@section('content')
<div class="row d-flex col-12 justify-content-between align-self-lg-center" style="margin-top: 100px">
    <div class="col-8 flex-fill m-4">
    <h1 class="fw-bolder" style="color: #111dc7"">iLab<span class="text" style="color: #27aae1">-Virtual Labs</span></h1>
            <h5 class="text-black m-3">Welcome, {{auth()->user()->name}}</h5>
    </div>
    <div class="col text-center">
        <img class="mb-4 " src="{{ URL::asset('/images/ilab_logo_ar.png')}}" alt="TUV-Labs" style="width: 200px">
    </div>
</div>
    <div class="row" style="margin: 45px 10px 75px;">
        <fieldset class="border border-4 rounded-3 p-3 ">
            <legend class="float-none w-auto px-3 h1" style="color: #27aae1"><strong>Admin</strong></legend>
            <div class="row justify-content-center">
                            <div class="col-10 col-sm-6 align-items-center mb-4">
                                <a href="{{ url('users') }}" class="btn btn-secondary w-100 fw-bolder " 
                                style="min-height: 80px; line-height: 80px;
                                text-align: center; ">Import Users</a>
                            </div>
                            <div class="col-10 col-sm-6 align-items-center mb-4">
                                <a href="{{ url('sections') }}" class="btn btn-secondary w-100 fw-bolder " 
                                style="min-height: 80px; line-height: 80px;
                                text-align: center; ">Import Sections</a>
                            </div>
                            <div class="col-10 col-sm-6 align-items-center mb-4">
                                <a href="{{ url('students') }}" class="btn btn-secondary w-100 fw-bolder " 
                                style="min-height: 80px; line-height: 80px;
                                text-align: center; ">Import Students</a>
                            </div>
                            <div class="col-10 col-sm-6 align-items-center mb-4">
                                <a href="{{ url('schedules') }}" class="btn btn-secondary w-100 fw-bolder " 
                                style="min-height: 80px; line-height: 80px;
                                text-align: center; ">Import Schedules </a>
                            </div>
                    <div class="col-10 align-items-center"></div>
            </div>
        </fieldset>
    </div>
@endsection
@section('script')
<script>
    const scheduleId = "1";
</script>
@vite('resources/js/general.js')
@endsection