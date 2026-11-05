@extends('layout')
@section('title', 'Instructor Session')
@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src='https://8x8.vc/external_api.js'></script>
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
    <fieldset class="border border-4 rounded-3 p-3 ">
        <legend class="float-none w-auto px-3 h3"><strong>Session</strong></legend>
          <div class="alert alert-danger" id="errorMessage" role="alert" style="display: none;"></div>
        <div class="row justify-content-center">
                <div class="col-10 col-sm-6 mt-2 align-items-center text-center fw-bold">
                    <div id="liveData" name="liveData" style="height: 480px;"></div>
                    {{-- <textarea class="form-control overflow-auto text-dark bg-light" id="liveData" name="liveData"
                        style="height: 480px;" rows="3"></textarea> --}}
                        <a href="{{ url('section') }}/{{ $schedule->section->id }}/{{ $schedule->id }}" class="btn btn-danger mt-2 me-md-2 btn-sm"><i class="fa fa-stop-circle-o"></i> End Session</a>  
                </div>
                <div class="col-10 col-sm-6 mt-2 align-items-center fw-bold" id="stdList">
                    <div class="alert alert-secondary text-center" role="alert">
                        LAB {{ $schedule->id }} {{ $schedule->week->name }}
                    </div>
                        @php $count = 1; @endphp
                    @foreach ($schedule->section->students as $student)
                        @php
                            $attendance = $schedule->attendances->where('std_id',$student->student->id);
                            $viewUserCode = $schedule->submissions->where('std_id',$student->student->id)->where('status',0)->first();
                            $submission = $schedule->submissions->where('std_id',$student->student->id)->where('status','<>',0)->first();
                        @endphp
                            <div class="row justify-content-center text-center bg-light m-1 p-2">
                                <div class="col-12 col-sm-5 align-items-center fw-bold">
                                {{ $count }}- {{ $student->student->name }}
                                </div>
                                <div id="std{{ $student->student->id }}" class="col-12 col-sm-2 align-items-center" style="color: {{ $attendance->count() >0 ? '#9fe2a2':'#b00202' }}">
                                    {{ $attendance->count() >0 ? 'Present':'Absent' }}
                                </div>
                                <div class="col-10 col-sm-3 align-items-center" id="stdCode-{{ $student->student->id }}">
                                    @if ($submission)
                                        <button class="btn btn-success btn-sm" onclick="getCode({{ $student->student->id }},{{ $schedule->id }},'{{ $student->student->name }}')" type="button"><i class="fa fa-file-code-o"></i> Completed</button>
                                    @else
                                        <button class="btn btn-secondary btn-sm" type="button" disabled><i class="fa fa-file-code-o"></i> Not Complete</button>
                                    @endif
                                    
                                    <button class="btn btn-info btn-sm mt-2 mb-2" name="viewCode" id="viewCode{{$student->student->id}}" onclick="viewCode({{ $student->student->id }},{{ $schedule->id }},'{{ $student->student->name }}')" type="button"><i class="fa fa-play-circle-o"></i> View</button>
                                </div>
                                <div class="col-10 col-sm-2 align-items-center" id="stdresult-{{ $student->student->id }}">
                                    @if ($submission)
                                        <input class="form-control form-control text-center" onchange="result('{{ $submission->id }}')" id="submit-{{ $submission->id }}" value="{{ $submission->results }}" type="number">
                                    @endif
                                </div>
                            </div>
                        @php $count++; @endphp
                    @endforeach
                </div>
                <div class="col-10 col-sm-6 mt-2 align-items-center fw-bold" id="stdEditor" style="display: none;">
                    <div class="alert alert-secondary text-center ms-3" role="alert">
                        <span class="position-absolute top-50 start-0 translate-middle badge bg-secondary">
                            <h4 class="mb-0" onclick="backStdList()"><i class="fa fa-arrow-circle-left text-white"></i> </h4>
                        </span>
                        <p class=" text-primary mb-0" id="editorStd">Student Name</p>
                        <input id="stdId" type="hidden" value="">
                    </div>
                    
                    <textarea class="form-control overflow-auto text-light bg-dark mt-2" id="editorData" name="editorData"
                        style="height: 200px;" rows="3"></textarea>
                    <div class="d-grid gap-2 m-4 d-md-flex text-center justify-content-md-center">
                        <button class="btn btn-primary me-md-2 btn-sm" id="runCode" type="button" ><i class="fa fa-play-circle-o"></i> Run</button>
                     </div>
                    <div class="card mt-2" style="height: 200px">
                        
                        <div class="card-body overflow-auto" id="outputData">

                        </div>
                      </div>
                </div>
        </div>
            
    </fieldset>
</div>
@endsection
@section('script')
<script>
    var roomName = 'S{{ $schedule->id }}-{{ $schedule->section->course->name }} {{ $schedule->section->name }}';
    var userName = '{{ auth()->user()->name }}';
    var userEmail = '{{ auth()->user()->email }}';
    const domain = '8x8.vc';
    const options = {
        roomName: 'vpaas-magic-cookie-d43bd9c418a349d89e1f18f99c826f01/'+roomName,
        //width: 320,
        height: 480,
        parentNode: document.querySelector('#liveData'),
        userInfo: {
                email: userEmail,
                displayName: userName
            },
        configOverwrite: {
        brandingRoomAlias: 'TUV Labs',
        hiddenPremeetingButtons: ['select-background', 'invite', 'settings'],
        startWithAudioMuted: true,
        startWithVideoMuted: true,
        disableModeratorIndicator: false,
        enableAutomaticUrlCopy: false,
        toolbarButtons: ['camera','microphone','whiteboard','chat','desktop','fullscreen',],
        readOnlyName: true,
        prejoinConfig: {
            enabled: false,
        }
        },
        
    };
    const api = new JitsiMeetExternalAPI(domain, options);
</script>
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
                data:{editorData:$('textarea#editorData').val(), stdId:$('#stdId').val(),schedule:'{{ $schedule->id }}'},
            })
            .done(function(data){
                $('#outputData').html(data.output);
                $( "#runCode" ).prop( "disabled", false );
                $("#runCode").html('<i class="fa fa-play-circle-o"></i> Run');
                $('#errorMessage').html('');
                $('#errorMessage').hide();
            })
            .fail(function(jqXHR,ajaxOptions,thrownError){
                $( "#runCode" ).prop( "disabled", false );
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
    function viewCode($std,$schedule,$stdName){
        $("#viewCode"+$std).prop("disabled",true);
        $("#viewCode"+$std).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
        $.ajax({
            type:'POST',
            url:"{{ route('get-code') }}",
            data:{std:$std, schedule: $schedule, viewCode:1},
        })
            .done(function(data){
                $('#editorStd').html($stdName);
                $('#stdId').val(data.std_id);
                $('#editorData').html(data.code);
                $('#outputData').html(data.output);
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
        document.getElementsByName('viewCode').forEach(function(ele, idx) {
            ele.innerHTML = '<i class="fa fa-play-circle-o"></i> View';
            ele.disabled = false;
        });
        $('#editorData').html('');
        $('#outputData').html('');
        $('#stdList').show();
        $('#stdEditor').hide();
    }
</script>
@vite('resources/js/general.js')
@endsection