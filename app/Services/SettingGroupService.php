<?php

namespace App\Services;

use App\Models\Icon;
use App\Models\SettingGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingGroupService
{
    public function getAllSettingGroup()
    {
        $settingGroups = SettingGroup::all();

        return $settingGroups;
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        DB::beginTransaction();
        try {
            $settingGroups = $this->getAllSettingGroup();

            $data = [
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'order' => (count($settingGroups) + 1),
            ];

            $icon = Icon::find($request->icon);
            if (!$icon) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Icon tidak ditemukan.'];
            }

            $icon->settingGroups()->create($data);

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menambahkan Grup Pengaturan.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function show(Request $request)
    {
        $settingGroup = SettingGroup::find($request->id);

        if (!$settingGroup) {
            return ['status' => 'error', 'message' => 'Grup Pengaturan yang Anda cari tidak ditemukan.', 'data' => ''];
        }

        return ['status' => 'success', 'message' => 'Berhasil mengambil data Grup Pengaturan', 'data' => $settingGroup, 'icon' => $settingGroup->icon];
    }

    public function update(Request $request)
    {
        $validator = $this->validator($request->all(), 'update', $request->id);
        if ($validator->fails()) {
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
            ];

            $settingGroup = SettingGroup::find($request->id);
            $settingGroup->update($data);

            if (!$settingGroup) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Gagal memperbarui grup pengaturan.'];
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil memperbarui grup pengaturan.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $settingGroup = SettingGroup::find($request->id);

            if (!$settingGroup) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Grup Pengaturan tidak ditemukan.'];
            }

            $getOrder = $settingGroup->order;
            $countSettingGroups = SettingGroup::count();

            for($i = $getOrder; $i <= $countSettingGroups; $i++) {

                if($i == $getOrder) continue;

                $getUpdateSettingGroup = SettingGroup::where(['order' => $i])->first();

                $getUpdateSettingGroup->order = ($getUpdateSettingGroup->order - 1);
                $getUpdateSettingGroup->save();
            }

            $settingGroup->delete();

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menghapus grup pengaturan.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function checkUnique(Request $request)
    {
        $id = $request->id;
        $slug = $request->slug;
        $type = $request->type;

        if ($type == 'insert') {
            $increment = 1;
            $settingGroup = SettingGroup::where('slug', $slug)->count();

            while (!$settingGroup == 0) {
                $increment++;
                $new_slug = $slug . '-' . $increment;
                $settingGroup = SettingGroup::where('slug', $new_slug)->count();
            }

            $slug = ($increment == 1 ? $slug : $slug . '-' . $increment);

            $response = ['status' => 'success', 'message' => 'Slug Tersedia.', 'data' => $slug];
        } else if ($type == 'update') {
            $settingGroup = SettingGroup::find($id);

            if ($settingGroup->slug == $slug) {
                return ['status' => 'success', 'message' => 'Slug Tersedia.', 'data' => $slug];
            }

            $increment = 1;
            $settingGroupCheck = SettingGroup::where('slug', $slug)->count();

            while (!$settingGroupCheck == 0) {
                $increment++;
                $new_slug = $slug . '-' . $increment;
                $settingGroupCheck = SettingGroup::where('slug', $new_slug)->count();
            }

            $slug = ($increment == 1 ? $slug : $slug . '-' . $increment);

            $response = ['status' => 'success', 'message' => 'Slug Tersedia.', 'data' => $slug];
        } else {
            $response = ['status' => 'error', 'message' => 'Tidak ada tindakan yang dilakukan.'];
        }

        return $response;
    }

    protected function validator(array $data, $type = 'insert', $id = 0)
    {
        $rules_slug = "";

        if ($type == 'insert') {
            $rules_slug = 'unique:setting_groups,slug';
        } else if ($type == 'update') {
            $rules_slug = 'unique:setting_groups,slug,' . $id;
        }

        $rules = [
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['required', 'string', 'max:191', $rules_slug],
            'description' => ['required', 'string']
        ];

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'string' => ':attribute harus berupa string',
            'max' => ':attribute maksimal :max karakter',
            'unique' => ':attribute yang Anda masukkan sudah terdaftar',
        ];

        return Validator::make($data, $rules, $messages);
    }
}
