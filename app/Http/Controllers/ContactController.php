<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactFormMail; // Import the Mailable class
use Illuminate\Support\Facades\Mail; // Import the Mail facade

class ContactController extends Controller
{
    // Show the contact form
    public function showContactForm()
    {
        return view('contact_us'); // Return the view for the contact us page
    }

    // Handle form submission
    public function submitContactForm(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Prepare the data to send
        $data = $request->only(['name', 'email', 'message']);

        // Send the email
        Mail::to('ilabteam.sa@gmail.com')->send(new ContactFormMail($data));

        // Redirect back with a success message
        return redirect()->route('contact_us')->with('success', 'Your message has been sent!');
    }
}
