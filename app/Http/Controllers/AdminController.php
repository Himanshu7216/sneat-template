<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(){
        return view('pages.dashboard');
    }
    public function analytics(){
        return view('pages.analytics');
    }
}
