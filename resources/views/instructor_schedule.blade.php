@extends('layout')
@section('title', 'Instructor Schedules')
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
                        Students<br>{{ $sectionData->students->count()}}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body text-secondary">
            <div class="row justify-content-center align-items-center">
                @if (Session::has('live_session'))
                    <div class="alert alert-success m-4 text-center" role="alert">
                        {{ Session::get('live_session') }}
                    </div>
                @endif
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
                            $attData = $schedule->attendances->where('std_id',auth()->user()->id);
                            $color = 'info';
                            $textColor = 'dark';
                            $status = 'Not yet started';
                            $live=false;
                            if ($newDate->lessThan($dateNow)) {
                                $color = 'secondary';
                                $textColor = 'white';
                                $status = 'Expired';
                            }
                            if ($dateNow->between($date, $newDate)) {
                                if ($endDate->lessThan($dateNow) || $schedule->status_id==3) {
                                    $color = 'warning';
                                    $status = 'Code submission';
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
                                        <div class="row text-center align-items-center m-2">
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
                                    <p class="card-text mt-2">{{ $sectionData->course->name }} - {{ $date->format('l') }} {{ $date->format('d/m/Y') }} at {{ $date->format('h:i:s A') }}.</p>
                                    
                                    @if ($live && $schedule->status_id==1)
                                        <a href="{{ url('start-session') }}/{{ $schedule->id }}" class="btn btn-success">Start Now</a>
                                    @else
                                        @switch($status)
                                            @case('Started')
                                                <a href="{{ url('start-session') }}/{{ $schedule->id }}" class="btn btn-success">Start Now</a>
                                                @break
                                            @case('Code submission')
                                                <div class="container text-center p-3 m-2"></div>
                                            @break
                                            @case('Not yet started')
                                                <a href="javascript:void(0)" class="btn btn-info disabled">Coming Soon</a>
                                                @break
                                            @default
                                                <a class="btn btn-secondary disabled">Expired</a>
                                        @endswitch
                                    @endif
                                </div>
                                <div class="card-footer">
                                    @if ($status != 'Not yet started')
                                        <div class="container text-center">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <a href="{{ url('schedule') }}/{{ $schedule->id }}/submissions" class="btn btn-{{ $schedule->submissions->count()>0? 'success' : 'danger'}}">Submissions : {{ $schedule->submissions->count() }}</a>
                                                </div>
                                                <div class="col">
                                                    <a href="{{ url('schedule') }}/{{ $schedule->id }}/attendances" class="btn btn-{{ $schedule->attendances->count()>0? 'success' : 'danger'}}">Attendances : {{ $schedule->attendances->count() }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="container text-center p-3"></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-10 col-sm-3 mb-2">
                        <a href="{{ url('marks') }}/{{$sectionData->id}}" class="btn btn-danger btn-lg w-100">Export Marks</a>
                    </div>
                    <div class="col-10 col-sm-3 mb-2">
                        <a href="{{ url('attendances') }}/{{$sectionData->id}}" class="btn btn-danger btn-lg w-100">Export Attendances</a>
                    </div>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-10 col-sm-3">
                            <a href="{{ url('live_session') }}/{{$sectionData->id}}" class="custom-button btn btn-lg w-100 fw-bolder">New Session</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    <div>
@endsection
