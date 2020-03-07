<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\RegisterService;
use App\Services\UserService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        return view('auth.register');
    }

    public function register(Request $request, RegisterService $registerService)
    {
        $register = $registerService->register($request);

        return response()->json($register);
    }

    public function checkUsername(Request $request, UserService $userService)
    {
        $checkUsername = $userService->checkUnique($request);

        return response()->json($checkUsername);
    }
}
