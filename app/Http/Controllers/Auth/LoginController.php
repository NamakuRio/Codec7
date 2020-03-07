<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request, LoginService $loginService)
    {
        $login = $loginService->web($request);

        return response()->json($login);
    }

    public function logout(Request $request, LoginService $loginService)
    {
        $logout = $loginService->logout($request);

        return redirect()->route('login')->with($logout['status'], $logout['message']);
    }
}
