<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index()
    {
        if (checkPermission('permission.view')) return view('pages.not-authorized');

        return view('admin.permission');
    }

    public function store(Request $request, PermissionService $permissionService)
    {
        if (checkPermission('permission.create')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $store = $permissionService->store($request);

        return response()->json($store);
    }

    public function show(Request $request, PermissionService $permissionService)
    {
        if (checkPermission('permission.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $show = $permissionService->show($request);

        return response()->json($show);
    }

    public function update(Request $request, PermissionService $permissionService)
    {
        if (checkPermission('permission.update')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $update = $permissionService->update($request);

        return response()->json($update);
    }

    public function destroy(Request $request, PermissionService $permissionService)
    {
        if (checkPermission('permission.delete')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $destroy = $permissionService->destroy($request);

        return response()->json($destroy);
    }

    public function getPermissions(PermissionService $permissionService)
    {
        if (checkPermission('permission.view')) abort(404);

        $permissions = $permissionService->getAllPermission();

        return DataTables::of($permissions)
            ->addColumn('action', function ($permission) {
                $action = "";

                if (!checkPermission('permission.update')) $action .= "<a href='javascript:void(0)' class='action-icon' data-id='$permission->id' onclick='getUpdateData(this)'><i class='mdi mdi-square-edit-outline text-info'></i></a>";
                if (!checkPermission('permission.delete')) $action .= "<a href='javascript:void(0)' class='action-icon' data-id='$permission->id' onclick='deletePermission(this)'><i class='mdi mdi-delete text-danger'></i></a>";

                return $action;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }
}
