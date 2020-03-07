<?php

namespace App\Services\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResetPasswordService
{
    public function checkToken(Request $request, $token = null)
    {
        if ($token == null) {
            return ['status' => 'error', 'message' => 'Token tidak valid.'];
        }

        $email = base64_decode($request->email);
        $data_reset = DB::select("SELECT * FROM password_resets WHERE email = '" . $email . "'");

        if (count($data_reset) == 0) {
            return ['status' => 'error', 'message' => 'Permintaan Anda tidak ada.'];
        }

        if (!password_verify($token, $data_reset[0]->token)) {
            return ['status' => 'error', 'message' => 'Token sudah kadaluarsa.'];
        }

        return ['status' => 'success', 'message' => 'Berhasil mendapatkan data', 'data' => ['email' => $email, 'token' => $token]];
    }

    public function reset(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
            ? ['status' => 'success', 'message' => 'Berhasil mengubah Kata Sandi.']
            : ['status' => 'error', 'message' => 'Gagal mengubah Kata Sandi.'];
    }

    public function broker()
    {
        return Password::broker();
    }

    protected function guard()
    {
        return Auth::guard();
    }

    protected function credentials(Request $request)
    {
        return $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );
    }

    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    protected function setUserPassword($user, $password)
    {
        $user->password = Hash::make($password);
    }

    protected function validator(array $data)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong',
            'email' => ':attribute yang Anda masukkan tidak valid',
            'confirmed' => ':attribute yang Anda masukkan tidak sama',
        ];

        return Validator::make($data, [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed'],
        ], $message);
    }
}
