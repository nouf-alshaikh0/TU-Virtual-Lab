@extends('layout')
@section('title', 'Instructor Submissions')
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
                Submissions
            </div>
                @php $count = 1; @endphp
            @foreach ($schedule->section->students as $student)
                @php
                    $attendance = $schedule->attendances->where('std_id',$student->student->id);
                    $submission = $schedule->submissions->where('std_id',$student->student->id)->where('status','<>',0)->first();
                @endphp
                    <div class="row justify-content-center text-center bg-light m-1 p-2">
                        <div class="col-12 col-sm-6 align-items-center fw-bold">
                        {{ $count }}- {{ $student->student->name }}
                        </div>
                        <div class="col-12 col-sm-2 align-items-center text-{{ $attendance->count() >0 ? 'success':'danger' }}">
                            {{ $attendance->count() >0 ? 'Present':'Absent' }}
                        </div>
                        <div class="col-12 col-sm-3 align-items-center" id="stdCode-{{ $student->student->id }}">
                            @if ($submission)
                                <button class="btn btn-success btn-sm" onclick="getCode({{ $student->student->id }},{{ $schedule->id }},'{{ $student->student->name }}')" type="button"><i class="fa fa-file-code-o"></i> Completed</button>
                            @else
                                <button class="btn btn-secondary btn-sm" type="button" disabled><i class="fa fa-file-code-o"></i> Not Complete</button>
                            @endif
                        </div>
                        <div class="col-12 col-sm-1 align-items-center" id="stdresult-{{ $student->student->id }}">
                            @if ($submission)
                                <input class="form-control form-control text-center" onchange="result('{{ $submission->id }}')" id="submit-{{ $submission->id }}" value="{{ $submission->results }}" type="number">
                            @endif
                        </div>
                    </div>
                @php $count++; @endphp
            @endforeach
        </div>
        <div class="col-12 mt-2 align-items-center fw-bold" id="stdEditor" style="display: none;">
            <div class="alert alert-secondary text-center ms-3" role="alert">
                <span class="position-absolute top-50 start-0 translate-middle badge bg-secondary">
                    <h4 class="mb-0" onclick="backStdList()"><i class="fa fa-arrow-circle-left text-white"></i> </h4>
                </span>
                <p class=" text-primary mb-0" id="editorStd">Student Name</p>
                <input id="stdId" type="hidden" value="">
            </div>
            <div class="row justify-content-center">
                <div class="col-10 col-sm-6 mt-2 align-items-center fw-bold">
                    <textarea class="form-control overflow-auto text-light bg-dark mt-2" id="editorData" name="editorData"
                    style="height: 350px;" rows="3"></textarea>
                    <div class="d-grid gap-2 m-4 d-md-flex text-center justify-content-md-center">
                        <button class="btn btn-primary me-md-2 btn-sm" id="runCode" type="button" ><i class="fa fa-play-circle-o"></i> Run</button>
                    </div>
                </div>
                <div class="col-10 col-sm-6 mt-2 align-items-center fw-bold">
                    <div class="card mt-2" style="height: 350px">
                        <div class="card-body overflow-auto" id="outputData">
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection
@section('script')
<script>
    const userId = "{{ auth()->user()->id }}";
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $(document).ready(function(){
        $("#runCode").click(function(){
            $("#runCode").prop("disabled",true);
            $("#runCode").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
           $('textarea#editorData').val();
           $.ajax({
                type:'POST',
                url:"{{ route('run-code') }}",
                data:{editorData:$('textarea#editorData').val(),stdId:$('#stdId').val(), schedule:'{{ $schedule->id }}'},
            })
            .done(function(data){
                $( "#runCode" ).prop( "disabled", false );
                $("#runCode").html('<i class="fa fa-play-circle-o"></i> Run');
                $('#outputData').html(data.output);
                $('#errorMessage').html('');
                $('#errorMessage').hide();
            })
            .fail(function(jqXHR,ajaxOptions,thrownError){
                $("#runCode").prop("disabled", false);
                $("#runCode").html('<i class="fa fa-play-circle-o"></i> Run');
                $('#errorMessage').html(jqXHR.responseJSON.message);
                $('#errorMessage').show();
            });
        });
    });
    
    function getCode($std,$schedule,$stdName){
        $.ajax({
            type:'POST',
            url:"{{ route('get-code') }}",
            data:{std:$std, schedule: $schedule},
        })
            .done(function(data){
                $('#editorStd').html($stdName);
                $('#stdId').val(data.std_id);
                $('#editorData').html(data.code);
                $('#outputData').html(data.output);
                $('#stdList').hide();
                $('#stdEditor').show();
            })
            .fail(function(jqXHR,ajaxOptions,thrownError){
                //$('#errorMessage').html(jqXHR.responseJSON.message);
                //$('#stdEditor').show();
            });
    }

    function result($submition){
        $.ajax({
            type:'POST',
            url:"{{ route('result') }}",
            data:{submition:$submition, result: $('input#submit-'+$submition).val()},
        })
            .done(function(data){
                console.log(data);
            })
            .fail(function(jqXHR,ajaxOptions,thrownError){
                //$('#errorMessage').html(jqXHR.responseJSON.message);
                //$('#stdEditor').show();
            });
    }

    function backStdList(){
        $('#editorData').html('');
        $('#outputData').html('');
        $('#stdList').show();
        $('#stdEditor').hide();
    }
</script>
@vite('resources/js/general.js')
@endsection