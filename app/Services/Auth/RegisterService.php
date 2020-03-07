<?php

namespace App\Services\Auth;

use App\Events\UserRegisteredEvent;
use App\Models\Role;
use App\Models\User;
use App\Notifications\Auth\SendMailActivationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class RegisterService
{
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        DB::beginTransaction();
        try {
            $data = [
                'username' => $request->username,
                'name' => $request->name,
                'email' => checkEmail($request->email),
                'password' => bcrypt($request->password),
                'phone' => checkPhone($request->phone),
            ];

            $user = User::create($data);

            $role = Role::find(defaultRole());
            if (!$role) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Untuk saat ini tidak dapat mendaftar.'];
            }

            $user->assignRole($role->name);
            Notification::route('mail', $user->email)->notify(new SendMailActivationNotification($user));
            // (new User)->forceFill([
            //     'name' => $user->name,
            //     'email' => $user->email,
            // ])->notify(new MailMessage);
            // event(new UserRegisteredEvent($user));

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menambahkan pengguna.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    protected function validator(array $data)
    {
        $data['email'] = checkEmail($data['email']);
        $data['phone'] = checkPhone($data['phone']);

        $rules_username = "";
        $rules_email = "";
        $rules_phone = "";
        $rules_password = "";

        $rules_username = 'unique:users,username';
        $rules_email = 'unique:users,email';
        $rules_phone = 'unique:users,phone';
        $rules_password = ['required', 'string'];

        $rules = [
            'username' => ['required', 'string', 'max:191', $rules_username],
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'max:191', 'email', $rules_email],
            'password' => ['max:191', $rules_password],
            'phone' => ['required', 'numeric', $rules_phone],
            'photo_url' => ['file', 'mimes:png,jpg,jpeg']
        ];

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'string' => ':attribute harus berupa string',
            'max' => ':attribute maksimal :max karakter',
            'unique' => ':attribute yang Anda masukkan sudah terdaftar',
            'email' => ':attribute harus berupa email',
            'numeric' => ':attribute harus berupa angka',
            'file' => ':atrribute harus berupa file',
            'mimes' => ':atrribute harus bertipe :mimes',
        ];

        return Validator::make($data, $rules, $messages);
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
