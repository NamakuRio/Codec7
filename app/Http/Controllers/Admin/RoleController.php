<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        if (checkPermission('role.view')) return view('pages.not-authorized');

        return view('admin.role');
    }

    public function store(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.create')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $store = $roleService->store($request);

        return response()->json($store);
    }

    public function show(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $show = $roleService->show($request);

        return response()->json($show);
    }

    public function update(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.update')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $update = $roleService->update($request);

        return response()->json($update);
    }

    public function destroy(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.delete')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $destroy = $roleService->destroy($request);

        return response()->json($destroy);
    }

    public function showManage(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.manage')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $showManage = $roleService->showManage($request);

        return response()->json($showManage);
    }

    public function manage(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.manage')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $manage = $roleService->manage($request);

        return response()->json($manage);
    }

    public function setDefault(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.manage')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $setDefault = $roleService->setDefault($request);

        return response()->json($setDefault);
    }

    public function getRoles(RoleService $roleService)
    {
        if (checkPermission('role.view')) abort(404);

        $roles = $roleService->getAllRole();

        return DataTables::of($roles)
            ->editColumn('default_user', function ($role) {
                $default_user = "";

                if ($role->default_user == 0) $default_user .= (checkPermission('role.manage') ? "<a href='javascript:void(0)' class='action-icon'><i class='dripicons-cross text-danger'></span></a>" : "<a href='javascript:void(0)' class='action-icon' data-id='$role->id' onclick='setDefault(this)'><i class='dripicons-cross text-danger'></span></a>");
                if ($role->default_user == 1) $default_user .= "<a href='javascript:void(0)' class='action-icon'><i class='dripicons-checkmark text-success'></span></a>";

                return $default_user;
            })
            ->editColumn('login_destination', function ($role) {
                $login_destination = url('/');

                $login_destination .= $role->login_destination[0] == '/' ? "<span class='text-primary'>$role->login_destination</span>" : "<span class='text-primary'>/$role->login_destination</span>";

                return $login_destination;
            })
            ->addColumn('action', function ($role) {
                $action = "";

                if (!checkPermission('role.manage')) $action .= "<a href='javascript:void(0)' class='action-icon' data-id='$role->id' onclick='getManageData(this)'><i class='mdi mdi-format-list-checks text-success'></i></a>";
                if (!checkPermission('role.update')) $action .= "<a href='javascript:void(0)' class='action-icon' data-id='$role->id' onclick='getUpdateData(this)'><i class='mdi mdi-square-edit-outline text-info'></i></a>";
                if (!checkPermission('role.delete')) $action .= "<a href='javascript:void(0)' class='action-icon' data-id='$role->id' onclick='deleteRole(this)'><i class='mdi mdi-delete text-danger'></i></a>";

                return $action;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function select2(Request $request)
    {
        $term = $request->data['term'];

        $search = Role::where('name', 'like', '%' . $term . '%')->get();

        return response()->json($search);
    }
}
