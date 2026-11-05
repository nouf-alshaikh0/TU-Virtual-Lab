@extends('layout')
@section('title', 'Submission Code')
@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endsection
@section('content')
<div class="row d-flex col-12 justify-content-between align-self-lg-center" style="margin-top: 100px">
    <div class="col-8 flex-fill m-4">
        <h1 class="fw-bolder" style="color: #111dc7">iLab <span class="text-black">Virtual Labs</span></h1>
            <h5 class="text-black m-3">Welcome, {{auth()->user()->name}}</h5>
    </div>
    <div class="col text-center">
        <img class="mb-4 " src="{{ URL::asset('/images/ilab_logo_ar.png')}}" alt="TUV-Labs" style="width: 200px">
    </div>
</div>
<div class="row col-10" style="margin: 20px 10px 75px;">
    @php
        $langId = $lang->id;
    @endphp
    <fieldset class="border border-4 rounded-3 p-3 ">
        <legend class="float-none w-auto px-3 h3"><strong>Practice [ {{ $lang->name }} ]</strong></legend>
          <div class="alert alert-danger" id="errorMessage" role="alert" style="display: none;">
            
          </div>
        <div class="row justify-content-center">
                <div class="col-10 col-sm-6 mt-2 align-items-center fw-bold">
                     ::/>Editor
                    <textarea class="form-control overflow-auto text-light bg-dark mt-2" onkeypress="inactiveSubmite()" id="editorData" name="editorData"
                        style="height: 350px;" rows="3"></textarea>
                    <div class="d-grid gap-2 mt-2 d-md-flex text-center justify-content-md-center">
                        <button class="btn btn-primary me-md-2 btn-lg" id="runCode" type="button">
                            <i class="fa fa-play-circle-o"></i> Run
                        </button>
                    </div>
                </div>
                <div class="col-10 col-sm-6 mt-2 align-items-center fw-bold">
                     ::/>Output:
                    <div class="card mt-2" style="height: 350px">
                        <div class="card-body overflow-auto" id="outputData"></div>
                      </div>
                </div>
        </div>
            
    </fieldset>
</div>
@endsection
@section('script')
<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $(document).ready(function(){
        $("#runCode").click(function(){
            $("#runCode").prop("disabled",true);
            $("#runCode").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
            $('textarea#editorData').val();
           $.ajax({
                type:'POST',
                url:"{{ route('run-practice') }}",
                data:{editorData:$('textarea#editorData').val(), langId:'{{ $langId }}'},
            })
            .done(function(data){
                $("#runCode").prop("disabled", false);
                $("#runCode").html('<i class="fa fa-play-circle-o"></i> Run');
                $('#outputData').html(data);
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
</script>
@endsection