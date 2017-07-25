<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('home')->with(['title' => 'CLIENT DASHBOARD']);
    }

    function tracker() {
        return view('tracker')->with(['title' => 'SAMPLE TRACKING']);
    }

    function finance() {
        return view('finance')->with(['title' => 'FIANANCE DASHBOARD']);
    }

    function samples() {
        return view('samples')->with(['title' => 'ANALYSIS REQUESTS']);
    }

    function request() {
        return view('new')->with(['title' => 'NEW ANALYSIS REQUESTS']);
    }
    
    function profile() {
        return view('profile')->with(['title' => 'PROFILE']);
    }

}
