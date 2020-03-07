<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettingGroup;
use App\Services\SettingGroupService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends Controller
{
    public function index(SettingGroup $settingGroup, SettingGroupService $settingGroupService)
    {
        if (checkPermission('setting.view')) return view('pages.not-authorized');

        $settingGroups = $settingGroupService->getAllSettingGroup();
        return view('admin.settings.detail.index', compact('settingGroup', 'settingGroups'));
    }

    public function store(SettingGroup $settingGroup, Request $request, SettingService $settingService)
    {
        if (checkPermission('setting.create')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $store = $settingService->store($settingGroup, $request);

        return response()->json($store);
    }

    public function show(SettingGroup $settingGroup, Request $request, SettingService $settingService)
    {
        if (checkPermission('setting.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $show = $settingService->show($settingGroup, $request);

        return response()->json($show);
    }

    public function update(SettingGroup $settingGroup, Request $request, SettingService $settingService)
    {
        if (checkPermission('setting.update')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $update = $settingService->update($settingGroup, $request);

        return response()->json($update);
    }

    public function destroy(SettingGroup $settingGroup, Request $request, SettingService $settingService)
    {
        if (checkPermission('setting.delete')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $destroy = $settingService->destroy($settingGroup, $request);

        return response()->json($destroy);
    }

    public function manage(SettingGroup $settingGroup, SettingGroupService $settingGroupService)
    {
        if (checkPermission('setting.create') || checkPermission('setting.update') || checkPermission('setting.manage')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        return view('admin.settings.detail.manage', compact('settingGroup'));
    }

    public function getSettings(SettingGroup $settingGroup)
    {
        if (checkPermission('setting.view')) abort(404);

        $settings = $settingGroup->settings;

        return DataTables::of($settings)
            ->editColumn('required', function ($setting) {
                $required = "";

                if($setting->required == 0) $required .= "<a href='javascript:void(0)' class='action-icon'><i class='dripicons-cross text-danger'></i></a>";
                if($setting->required == 1) $required .= "<a href='javascript:void(0)' class='action-icon'><i class='dripicons-checkmark text-success'></i></a>";

                return $required;
            })
            ->addColumn('setting_group_name', function ($setting) {
                $setting_group_name = $setting->settingGroup->name;

                return $setting_group_name;
            })
            ->addColumn('action', function ($setting) {
                $action = "";

                if (!checkPermission('setting.update')) $action .= "<a href='javascript:void(0)' class='action-icon' data-id='$setting->id' onclick='getUpdateData(this)'><i class='mdi mdi-square-edit-outline text-info'></i></a>";
                if (!checkPermission('setting.delete')) $action .= "<a href='javascript:void(0)' class='action-icon' data-id='$setting->id' onclick='deleteSetting(this)'><i class='mdi mdi-delete text-danger'></i></a>";

                return $action;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function checkName(Request $request, SettingService $settingService)
    {
        $checkName = $settingService->checkUnique($request);

        return response()->json($checkName);
    }

    public function save(SettingGroup $settingGroup, Request $request, SettingService $settingService)
    {
        if (checkPermission('setting.update')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $save = $settingService->save($settingGroup, $request);

        return response()->json($save);
    }

    public function reset(SettingGroup $settingGroup, Request $request, SettingService $settingService)
    {
        if (checkPermission('setting.update')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $reset = $settingService->reset($settingGroup, $request);

        return response()->json($reset);
    }
}
