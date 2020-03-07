<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(UserService $userService)
    {
        $countAllUser = $userService->count();

        return view('admin.dashboard', compact('countAllUser'));
    }
}
