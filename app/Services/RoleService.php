<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleService
{
    public function getAllRole()
    {
        $roles = Role::all();

        return $roles;
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
                'login_destination' => $request->login_destination,
            ];

            Role::create($data);

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menambahkan peran.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function show(Request $request)
    {
        $role = Role::find($request->id);

        if (!$role) {
            return ['status' => 'error', 'message' => 'Peran yang Anda cari tidak ditemukan.', 'data' => ''];
        }

        return ['status' => 'success', 'message' => 'Berhasil mengambil data Peran', 'data' => $role];
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
                'login_destination' => $request->login_destination,
            ];

            $role = Role::find($request->id);
            $role->update($data);

            if (!$role) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Gagal memperbarui peran.'];
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil memperbarui peran.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $role = Role::find($request->id);

            if (!$role) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Peran tidak ditemukan.'];
            }

            $role->delete();

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menghapus peran.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function showManage(Request $request)
    {
        $permissions = Permission::all();
        $role = Role::find($request->id);
        $view = "";

        $role_permissions = $this->get_role_permission($role->id);

        $no = 1;
        $rows = array();
        $header = array();
        $tmp = array();

        foreach ($permissions as $permission) {
            $x = explode(".", $permission->name);
            if (!in_array($x[1], $header)) {
                $header[] = $x[1];
                $tmp[$x[1]] = 0;
            }
        }

        foreach ($permissions as $permission) {
            $x = explode(".", $permission->name);
            $rows[$x[0]] = $tmp;
        }

        foreach ($permissions as $key => $permission) {
            $x = explode(".", $permission->name);
            //Rows
            $rows[$x[0]][$x[1]] = array('id' => $permission->id, 'action_name' => $x[1], 'is_role_permission' => (isset($role_permissions[$permission->id]->is_role_permission) && $role_permissions[$permission->id]->is_role_permission == 1) ? 1 : '', 'value' => (isset($role_permissions[$permission->id]) ? 1 : 0));
        }

        $view .= '<input type="hidden" id="manage-role-id" name="id" value="' . $role->id . '" required>';
        $view .= '<div class="table-responsive">';

        $view .= '<table class="table table-bordered table-hover text-center">';

        $view .= '<thead>';
        $view .= '<tr>';
        $view .= '<td>#</td>';
        $view .= '<td>Izin</td>';
        foreach ($header as $hd) {
            $view .= '<td>' . ucfirst($hd) . '</td>';
        }
        $view .= '</tr>';
        $view .= '</thead>';

        $view .= '<tbody>';
        foreach ($rows as $key => $row) {
            $view .= '<tr>';
            $view .= '<td>' . $no++ . '</td>';
            $view .= '<td>' . $key . '</td>';
            foreach ($row as $action) {
                $view .= '<td>';
                if ($action == 0) {
                    $view .= '-';
                } else {
                    $checked = '';

                    if ($action['value'] == 1) $checked = 'checked';

                    $view .= '<div class="custom-control custom-switch">';
                    $view .= '<input type="checkbox" class="custom-control-input" name="permission[]" value="' . $action['id'] . '" id="customSwitch' . $action['id'] . '" ' . $checked . '>';
                    $view .= '<label class="custom-control-label" for="customSwitch' . $action['id'] . '"></label>';
                    $view .= '</div>';
                }
                $view .= '</td>';
            }
            $view .= '</tr>';
        }
        $view .= '</tbody>';

        $view .= '</table>';

        $view .= '</div>';

        return ['status' => 'success', 'message' => 'Berhasil mengambil data Manage Peran', 'data' => $view];
    }

    public function manage(Request $request)
    {
        DB::beginTransaction();
        try {
            $permissions = $request->permission;
            $role = Role::find($request->id);

            if (empty($permissions)) {
                $role->permissions()->detach();

                DB::commit();
                return ['status' => 'success', 'message' => 'Berhasil mengubah data izin peran.'];
            }

            for ($i = 0; $i < count($permissions); $i++) {
                $perms[] = Permission::find($permissions[$i]);
            }

            foreach ($perms as $perm) {
                $data[] = $perm->name;
            }

            if (!empty($role)) {
                $role->updatePermissions($data);
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil mengubah data izin peran.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function setDefault(Request $request)
    {
        DB::beginTransaction();
        try {
            $roles = Role::all();
            $success = 0;

            foreach ($roles as $role) {
                if ($role->id == $request->id) {
                    $update = $role->update(['default_user' => 1]);
                } else {
                    $update = $role->update(['default_user' => 0]);
                }

                if ($update) {
                    $success = $success + 1;
                }
            }

            if ($roles->count() != $success) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Gagal mengubah pengguna default peran.'];
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil mengubah pengguna default peran.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    protected function validator(array $data, $type = 'insert', $id = 0)
    {
        $rules_name = "";
        if ($type == 'insert') {
            $rules_name = 'unique:roles,name';
        } else if ($type == 'update') {
            $rules_name = 'unique:roles,name,' . $id;
        }

        $rules = [
            'name' => ['required', 'string', 'max:191', $rules_name],
            'login_destination' => ['required', 'string', 'max:191'],
        ];

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'string' => ':attribute harus berupa string',
            'max' => ':attribute maksimal :max karakter',
            'unique' => ':attribute yang Anda masukkan sudah terdaftar'
        ];

        return Validator::make($data, $rules, $messages);
    }

    protected function get_role_permission($id)
    {
        $role = Role::find($id);

        $permissions = $role->permissions;

        $merge = array();

        if ($permissions) {
            foreach ($permissions as $permission) {
                $permission->is_role_permission = 1;
                $merge[$permission->id] = $permission;
            }
        }

        return $merge;
    }
}
