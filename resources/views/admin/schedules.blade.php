@extends('layout')
@section('title', 'Import Schedules')
@section('content')
<div class="row d-flex col-12 justify-content-between align-self-lg-center" style="margin-top: 100px">
    <div class="col-8 flex-fill m-4">
    <h1 class="fw-bolder" style="color: #111dc7">iLab<span class="text" style="color: #27aae1">-Virtual Labs</span></h1>
            <h5 class="text-black m-3">Welcome, {{auth()->user()->name}}</h5>
    </div>
    <div class="col text-center">
        <img class="mb-4 " src="{{ URL::asset('/images/ilab_logo_ar.png')}}" alt="TUV-Labs" style="width: 200px">
    </div>
</div>
    <div class="row" style="margin: 45px 10px 75px;">
        <fieldset class="border border-4 rounded-3 p-3 ">
            <legend class="float-none w-auto px-3 h1" style="color: #27aae1"><strong>Schedules</strong></legend>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importSchedulesModal">
                        Import Schedules
                    </button>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('success_message'))
                    <div class="alert alert-success text-center mt-4" role="alert">
                        {{ Session::get('success_message') }}
                    </div>
                @endif
                @if (Session::has('message'))
                    <div class="alert alert-danger text-center mt-4" role="alert">
                        {{ Session::get('message') }}
                    </div>
                @endif
                <table class="table table-striped table-hover table-bordered text-center mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Section</th>
                            <th>Week</th>
                            <th>Date / Time</th>
                            <th>Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->id }}</td>
                                <td>{{ $schedule->section->name }}</td>
                                <td>{{ $schedule->week->name }}</td>
                                <td>{{ $schedule->date }}</td>
                                <td>{{ $schedule->hrs }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </fieldset>
        <div class="modal fade" id="importSchedulesModal" tabindex="-1" aria-labelledby="importSchedulesModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Import Schedules</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('import_schedules') }}" class="needs-validation mt-4 pt-2" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Schedules Excel File [xlsx] </label>
                            <input class="form-control" type="file" id="schedules_file" name="schedules_file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
    </div>
@endsection
