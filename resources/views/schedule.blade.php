@extends('layout')
@section('title', 'Schedules')
@section('content')
    <div class="row d-flex col-12 justify-content-between align-self-lg-center" style="margin-top: 100px">
        <div class="col-8 flex-fill m-4">
        <h1 class="fw-bolder" style="color: #111dc7">iLab<span class="text" style="color: #27aae1">-Virtual Labs</span></h1>
            <h5 class="text-black m-3">Welcome, {{ auth()->user()->name }}</h5>
        </div>
        <div class="col text-center">
            <img class="mb-4 " src="{{ URL::asset('/images/ilab_logo_ar.png') }}" alt="TUV-Labs" style="width: 200px">
        </div>
    </div>
    <div data-bs-spy="scroll" class="card col-10 border-secondary" style="margin-bottom: 100px">
        <div class="card-header" style="background-color: #27aae1">
            <div class="container text-center">
                <div class="row align-items-center text-white fw-bold">
                    <div class="col">
                        Section<br>{{ $sectionData->name }}
                    </div>
                    <div class="col">
                        Course<br>{{ $sectionData->course->name }}
                    </div>
                    <div class="col">
                        Instructor <br> {{ $sectionData->instructor->name }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body text-secondary">
            <div class="row justify-content-center align-items-center">
                @if (Session::has('message'))
                    <div class="alert alert-danger m-4 text-center" role="alert">
                        {{ Session::get('message') }}
                    </div>
                @endif
                @if ($sectionData->schedules->count()==0)
                    <div class="alert alert-warning m-4 text-center" role="alert">
                        This section does not have any schedules
                    </div>
                @else
                    @foreach ($sectionData->schedules as $schedule)
                        @php
                            $date = Carbon\Carbon::parse($schedule->date);
                            $dateNow = Carbon\Carbon::now();
                            $newDate = Carbon\Carbon::parse($schedule->date)->addHours($schedule->hrs * 1 + 24);
                            $endDate = Carbon\Carbon::parse($schedule->date)->addHours($schedule->hrs);
                            $submitData = $schedule->submissions->where('std_id',auth()->user()->id)->first();
                            $attData = $schedule->attendances->where('std_id',auth()->user()->id)->first();
                            $color = 'info';
                            $textColor = 'dark';
                            $status = 'Not yet started';
                            $att='';
                            $live=false;
                            if ($newDate->lessThan($dateNow)) {
                                $color = 'secondary';
                                $textColor = 'white';
                                $status = 'Expired';
                                $att = $attData ? 'Present':'Absent';
                            }
                            if ($dateNow->between($date, $newDate)) {
                                if ($endDate->lessThan($dateNow) || $schedule->status_id==3) {
                                    $color = 'warning';
                                    $status = 'Code submission';
                                    $att = $attData ? 'Present':'Absent';
                                }else {
                                    $color = 'success';
                                    $textColor = 'white';
                                    $live=true;
                                    $status = 'Started';
                                }
                            }
                        @endphp
                        <div class="col-10 col-sm-6 mb-4 mt-4">
                            <div class="card border-{{ $color }}">
                                <span
                                    class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-{{ $color }}">
                                    <h6 class="text-{{ $textColor }}">{{ $status }}</h6>
                                </span>
                                <div class="card-header bg-{{ $color }} text-{{ $textColor }}">
                                    <div class="container">
                                        <div class="row text-center m-2">
                                            <div class="col">
                                                {{ $schedule->week->name }}
                                            </div>
                                            <div class="col">
                                                {{ $date->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body text-secondary text-center">
                                    <p class="card-text mt-2">{{ $sectionData->course->name }} -
                                        {{ $sectionData->students[0]->semester->name }} - {{ $date->format('l') }}.</p>
                                    <div id="schedule{{ $schedule->id }}">
                                        @if ($live && $schedule->status_id==1)
                                            <a href="{{ url('start-session') }}/{{ $schedule->id }}" class="btn btn-success">Start Now</a>
                                        @else
                                            @switch($status)
                                                @case('Started')
                                                    <a href="{{ url('start-session') }}/{{ $schedule->id }}" class="btn btn-success">Start Now</a>
                                                    @break
                                                @case('Code submission')
                                                    @if ($submitData)
                                                        <a href="{{ url('view-code') }}/{{ $submitData->id }}" class="btn btn-warning">View Code</a>
                                                    @else
                                                        <a href="{{ url('new-code') }}/{{ $schedule->id}}" class="btn btn-warning">Submit Code</a>
                                                    @endif
                                                    @break
                                                @case('Not yet started')
                                                    <a href="#" class="btn btn-info disabled">Coming Soon</a>
                                                    @break
                                                @default
                                                @if ($submitData)
                                                        <a href="{{ url('view-code') }}/{{ $submitData->id }}" class="btn btn-secondary">View Code</a>
                                                    @else
                                                        <a class="btn btn-secondary disabled">Expired</a>
                                                    @endif
                                                    
                                            @endswitch
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="container text-center">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                Start at {{ $date->format('h:i:s A') }}
                                            </div>
                                            <div class="col">
                                                End at {{ $endDate->format('h:i:s A') }}
                                            </div>
                                            <div text-center>
                                                <h6 class="fw-bolder text-{{ $att=='Absent'?'danger':'success' }}">{{ $att }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    <div>
@endsection
@section('script')
<script>
    const userId = "{{ auth()->user()->id }}";
</script>
@vite('resources/js/general.js')
@endsection
