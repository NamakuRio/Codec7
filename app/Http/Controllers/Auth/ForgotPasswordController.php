<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\ForgotPasswordService;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        return view('auth.passwords.email');
    }

    public function forgot(Request $request, ForgotPasswordService $forgotPasswordService)
    {
        $result = $forgotPasswordService->sendResetLinkEmail($request);

        return response()->json($result);
    }
}
