@extends('layout')
@section('title', 'Instructor Attendances')
@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endsection
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
    <div class="row col-10" style="margin: 20px 10px 75px;">
        <div class="col-12 mt-2 align-items-center fw-bold" id="stdList">
            <div class="alert alert-secondary text-center" role="alert">
                Attendances
            </div>
                @php $count = 1; @endphp
            @foreach ($schedule->section->students as $student)
                @php
                    $attendance = $schedule->attendances->where('std_id',$student->student->id);$submission = $schedule->submissions->where('std_id',$student->student->id)->where('status','<>',0)->first();
                @endphp
                    <div class="row justify-content-center text-center bg-light m-1 p-2">
                        <div class="col-12 col-sm-8 align-items-center fw-bold">
                        {{ $count }}- {{ $student->student->name }}
                        </div>
                        <div class="col-12 col-sm-4 align-items-center text-{{ $attendance->count() >0 ? 'success':'danger' }}">
                            {{ $attendance->count() >0 ? 'Present':'Absent' }}
                        </div>
                    </div>
                @php $count++; @endphp
            @endforeach
        </div>
    </div>
@endsection