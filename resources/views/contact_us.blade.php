@extends('layout') <!-- This should match your layout file name -->

@section('title', 'Contact Us') <!-- Set the title for the page -->

@section('content') <!-- Begin the content section -->
    <div class="container mt-5"> <!-- Add margin for proper display -->
        <h1>Contact Us</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('contact_us.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Message:</label>
                <textarea name="message" id="message" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn custom-button">Send Message</button>
        </form>
    </div>
@endsection <!-- End the content section -->
