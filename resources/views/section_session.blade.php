@extends('layout')
@section('title', 'Session')
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
                <div class="col-10 col-sm-6 mt-2 align-items-center fw-bold">
                     Live:
                     <div id="liveData" name="liveData" style="height: 385px;"></div>
                    {{-- <textarea class="form-control overflow-auto text-light bg-dark mt-2" id="liveData" name="liveData"
                        style="height: 385px;" rows="3"></textarea> --}}
                    <p class="text-center mt-4"><i class="fa fa-user"></i><br> {{ $schedule->section->instructor->name }}</p>
                </div>
                <div class="col-10 col-sm-6 mt-2 align-items-center fw-bold">
                    Editor:
                     <textarea class="form-control overflow-auto text-light bg-dark mt-2" id="editorData" name="editorData"
                        style="height: 200px;" rows="3">{{ $schedule->submission ? $schedule->submission->code : '' }}</textarea>
                        <div class="d-grid gap-2 m-4 d-md-flex text-center justify-content-md-center">
                            <button class="btn btn-primary me-md-2 btn-sm" id="runCode" type="button" ><i class="fa fa-play-circle-o"></i> Run</button>
                            <button class="btn btn-success btn-sm" id="submitCode" type="button"><i class="fa fa-file-code-o"></i> Submit</button>
                        </div>
                    <div class="card mt-2" style="height: 200px">
                        <div class="card-body overflow-auto" id="outputData">
                            {!! $schedule->submission ? $schedule->submission->output : '' !!}
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
        height: 400,
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
                data:{editorData:$('textarea#editorData').val(), schedule:'{{ $schedule->id }}'},
            })
            .done(function(data){
                $( "#submitCode" ).prop( "disabled", false );
                $("#runCode").prop("disabled", false);
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
        $("#submitCode").click(function(){
            $("#submitCode").prop("disabled",true);
            $("#submitCode").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
            $.ajax({
                type:'POST',
                url:"{{ route('submit-code') }}",
                data:{editorData:$('textarea#editorData').val(), schedule:'{{ $schedule->id }}'},
            })
            .done(function(data){
                $("#runCode").prop("disabled",false);
                $("#submitCode").html('<i class="fa fa-file-code-o"></i> Submit');
                $('#outputData').html(data.output);
                $('#errorMessage').html('');
                $('#errorMessage').hide();
            })
            .fail(function(jqXHR,ajaxOptions,thrownError){
                $("#runCode").prop("disabled",false);
                $("#submitCode").prop("disabled",false);
                $("#submitCode").html('<i class="fa fa-file-code-o"></i> Submit');
                $('#errorMessage').html(jqXHR.responseJSON.message);
                $('#errorMessage').show();
            });
        });
    });
</script>
@vite('resources/js/general.js')
@endsection