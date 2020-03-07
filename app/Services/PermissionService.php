<?php

namespace App\Services;

use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissionService
{
    public function getAllPermission()
    {
        $permissions = Permission::all();

        return $permissions;
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'guard_name' => $request->guard_name,
            ];

            Permission::create($data);

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menambahkan izin.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function show(Request $request)
    {
        $permission = Permission::find($request->id);

        if (!$permission) {
            return ['status' => 'error', 'message' => 'Izin yang Anda cari tidak ditemukan.', 'data' => ''];
        }

        return ['status' => 'success', 'message' => 'Berhasil mengambil data izin', 'data' => $permission];
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
                'guard_name' => $request->guard_name,
            ];

            $permission = Permission::find($request->id);
            $permission->update($data);

            if (!$permission) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Gagal memperbarui izin.'];
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil memperbarui izin.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $permission = Permission::find($request->id);

            if (!$permission) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Izin tidak ditemukan.'];
            }

            $permission->delete();

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menghapus izin.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    protected function validator(array $data, $type = 'insert', $id = 0)
    {
        $rules_name = "";
        if ($type == 'insert') {
            $rules_name = 'unique:permissions,name';
        } else if ($type == 'update') {
            $rules_name = 'unique:permissions,name,' . $id;
        }

        $rules = [
            'name' => ['required', 'string', 'max:191', $rules_name],
            'guard_name' => ['required', 'string', 'max:191'],
        ];

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'string' => ':attribute harus berupa string',
            'max' => ':attribute maksimal :max karakter',
            'unique' => ':attribute yang Anda masukkan sudah terdaftar'
        ];

        return Validator::make($data, $rules, $messages);
    }
}
