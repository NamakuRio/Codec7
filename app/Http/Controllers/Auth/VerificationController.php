<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        return view('auth.emails.verification');
    }

    public function verify(Request $request)
    {
        return view('auth.emails.verify');
    }

    public function resend(Request $request)
    {

    }
}
