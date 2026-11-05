@extends('layout')
@section('title', 'Student Darshboard')
@section('content')
<div class="row d-flex col-12 justify-content-between align-self-lg-center" style="margin-top: 100px">
    <div class="col-8 flex-fill m-4">
        <h1 class="fw-bolder" >Practice Labs</h1>
                <h4 class="fw-bolder" style="color: #111dc7; margin-left: 60px">Here you can practice!</h4>
    </div>
    <div class="col text-center">
        <img class="mb-4 " src="{{ URL::asset('/images/ilab_logo_ar.png')}}" alt="TUV-Labs" style="width: 200px">
    </div>
</div>
    <div class="row d-flex col-12 justify-content-between" style="margin: 5px 10px 75px;">
            <div class="col-10 col-sm-6 text-left p-3">
                <img style="margin-left: 60px" src="{{ URL::asset('/images/Prac-page.png')}}" alt="TUV-Labs" >
            </div>
            <div class="col-10 col-sm-6 p-4 text-center">
                <div class="row">
                    @foreach ($langs as $lang)
                    <div class="col-3 align-items-center m-2">
                        <a href="{{ url('practice-lab') }}/{{ $lang->id }}" class="custom-button btn btn-lg w-100 fw-bolder">{{ $lang->name }}</a>
                    </div>
                    @endforeach
                </div>
            </div>
    </div>
@endsection

@section('script')
<script>
    const userId = "{{ auth()->user()->id }}";
</script>
@vite('resources/js/general.js')
@endsection
