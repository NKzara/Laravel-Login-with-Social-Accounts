<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * Display the info page
     */
    public function index()
    {
        return view("about");
    }

    /**
     * Show the contact us page
     */
    public function contactUs()
    {
        return view("contact_us");
    }

    
}
