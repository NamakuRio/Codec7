<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingGroupService;
use Illuminate\Http\Request;

class SettingGroupController extends Controller
{
    public function index()
    {
        if (checkPermission('setting_group.view')) return view('pages.not-authorized');

        return view('admin.settings.index');
    }

    public function store(Request $request, SettingGroupService $settingGroupService)
    {
        if (checkPermission('setting_group.create')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $store = $settingGroupService->store($request);

        return response()->json($store);
    }

    public function show(Request $request, SettingGroupService $settingGroupService)
    {
        if (checkPermission('setting_group.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $show = $settingGroupService->show($request);

        return response()->json($show);
    }

    public function update(Request $request, SettingGroupService $settingGroupService)
    {
        if (checkPermission('setting_group.update')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $update = $settingGroupService->update($request);

        return response()->json($update);
    }

    public function destroy(Request $request, SettingGroupService $settingGroupService)
    {
        if (checkPermission('setting_group.delete')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $destroy = $settingGroupService->destroy($request);

        return response()->json($destroy);
    }

    public function getSettingGroups(SettingGroupService $settingGroupService)
    {
        if (checkPermission('setting_group.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $settingGroups = $settingGroupService->getAllSettingGroup();
        $view = view('admin.settings.data', compact('settingGroups'))->render();

        return response()->json(['status' => 'success', 'message' => 'Berhasil mendapatkan data pengaturan.', 'data' => $view]);
    }

    public function checkSlug(Request $request, SettingGroupService $settingGroupService)
    {
        $checkSlug = $settingGroupService->checkUnique($request);

        return response()->json($checkSlug);
    }
}
