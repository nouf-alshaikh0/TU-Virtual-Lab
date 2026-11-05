@extends('layout')
@section('title', 'Import Students')
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
            <legend class="float-none w-auto px-3 h1" style="color: #27aae1"><strong>Students</strong></legend>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importStudentsModal">
                        Import Students
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
                            <th>Student Name</th>
                            <th>Semester</th>
                            <th>Section</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->student->name }}</td>
                                <td>{{ $student->semester->name }}</td>
                                <td>{{ $student->section->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </fieldset>
        <div class="modal fade" id="importStudentsModal" tabindex="-1" aria-labelledby="importStudentsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel"> Import Students</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('import_std_section') }}" class="needs-validation mt-4 pt-2" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="formFile" class="form-label"> Students Excel File [xlsx]</label>
                            <input class="form-control" type="file" id="stds_file" name="stds_file" required>
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
