<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        return view('admin.account');
    }

    public function update(Request $request, UserService $userService)
    {
        $request->request->add(['id' => auth()->user()->id]);
        $request->request->add(['role' => auth()->user()->roles->first()->id]);

        $update = $userService->update($request);

        return response()->json($update);
    }
}
