<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function showAboutUs()
    {
        return view('about_us'); // Return the view for About Us page
    }
}
