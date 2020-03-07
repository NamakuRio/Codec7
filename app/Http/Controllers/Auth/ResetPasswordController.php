<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\ResetPasswordService;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index(Request $request, $token = null, ResetPasswordService $resetPasswordService)
    {
        $checkToken = $resetPasswordService->checkToken($request, $token);
        if ($checkToken['status'] != 'success') {
            return redirect()->route('login')->with($checkToken['status'], $checkToken['message']);
        }

        return view('auth.passwords.reset')->with($checkToken['data']);
    }

    public function reset(Request $request, ResetPasswordService $resetPasswordService)
    {
        $result = $resetPasswordService->reset($request);

        return response()->json($result);
    }
}
