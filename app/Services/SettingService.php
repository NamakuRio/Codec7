<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\SettingGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingService
{
    public function store(SettingGroup $settingGroup, Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        DB::beginTransaction();
        try {
            $settings = $settingGroup->settings;

            $data = [
                'name' => $request->name,
                'value' => $request->value,
                'default_value' => $request->default_value,
                'type' => $request->type,
                'comment' => $request->comment,
                'required' => ($request->required ? $request->required : 0),
                'order' => (count($settings) + 1),
            ];

            $settingGroup->settings()->create($data);

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menambahkan Pengaturan.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function show(SettingGroup $settingGroup, Request $request)
    {
        $setting = $settingGroup->settings()->find($request->id);

        if (!$setting) {
            return ['status' => 'error', 'message' => 'Pengaturan yang Anda cari tidak ditemukan.', 'data' => ''];
        }

        return ['status' => 'success', 'message' => 'Berhasil mengambil data Pengaturan', 'data' => $setting];
    }

    public function update(SettingGroup $settingGroup, Request $request)
    {
        $validator = $this->validator($request->all(), 'update', $request->id);
        if ($validator->fails()) {
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'value' => $request->value,
                'default_value' => $request->default_value,
                'type' => $request->type,
                'comment' => $request->comment,
                'required' => ($request->required ? $request->required : 0),
            ];

            $setting = $settingGroup->settings()->find($request->id);
            if (!$setting) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Gagal memperbarui pengaturan.'];
            }

            $setting->update($data);

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil memperbarui pengaturan.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function destroy(SettingGroup $settingGroup, Request $request)
    {
        DB::beginTransaction();
        try {
            $setting = $settingGroup->settings()->find($request->id);

            if (!$setting) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Pengaturan tidak ditemukan.'];
            }

            $getOrder = $setting->order;
            $countSettings = count($settingGroup->settings);

            for ($i = $getOrder; $i <= $countSettings; $i++) {

                if ($i == $getOrder) continue;

                $getUpdateSetting = $settingGroup->settings()->where(['order' => $i])->first();

                $getUpdateSetting->order = ($getUpdateSetting->order - 1);
                $getUpdateSetting->save();
            }

            $setting->delete();

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menghapus pengaturan.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function checkUnique(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $type = $request->type;

        if ($type == 'insert') {
            $setting = Setting::where('name', $name)->count();

            while (!$setting == 0) {
                return ['status' => 'error', 'message' => 'Nama Tidak Tersedia.'];
            }

            $response = ['status' => 'success', 'message' => 'Nama Tersedia.'];
        } else if ($type == 'update') {
            $setting = Setting::find($id);

            if ($setting->name == $name) {
                return ['status' => 'success', 'message' => 'Nama Tersedia.'];
            }

            $settingCheck = Setting::where('name', $name)->count();

            while (!$settingCheck == 0) {
                return ['status' => 'error', 'message' => 'Nama Tidak Tersedia.'];
            }

            $response = ['status' => 'success', 'message' => 'Nama Tersedia.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Tidak ada tindakan yang dilakukan.'];
        }

        return $response;
    }

    public function save(SettingGroup $settingGroup, Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($settingGroup->settings as $record) {
                $data[$record->name] = ($request->all()[$record->name] ?? NULL);
            }

            foreach ($data as $key => $record) {
                if ($record == NULL) continue;

                $setting = $settingGroup->settings()->where(['name' => $key])->first();

                $setting->value = $record;
                $setting->save();
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil memperbarui pengaturan.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function reset(SettingGroup $settingGroup, Request $request)
    {
        DB::beginTransaction();
        try {
            $settings = $settingGroup->settings;

            foreach($settings as $key => $record){
                $setting = $settingGroup->settings()->find($record->id);

                $setting->value = NULL;
                $setting->save();
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil atur ulang pengaturan.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    protected function validator(array $data, $type = 'insert', $id = 0)
    {
        $rules_name = "";

        if ($type == 'insert') {
            $rules_name = 'unique:settings,name';
        } else if ($type == 'update') {
            $rules_name = 'unique:settings,name,' . $id;
        }

        $rules = [
            'name' => ['required', 'string', 'max:191', $rules_name],
            'value' => [],
            'default_value' => [],
            'type' => ['required', 'string'],
            'comment' => [],
            'required' => []
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
