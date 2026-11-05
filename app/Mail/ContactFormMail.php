<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data; // Store the form data
    }

    public function build()
    {
        return $this->subject('New Contact Form Submission')
                    ->view('auth.emails.contact_form'); // Point to the correct view path
    }
}
