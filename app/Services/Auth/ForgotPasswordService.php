<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Notifications\Auth\SendResetPasswordLinkEmailNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordService
{
    public function sendResetLinkEmail(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        DB::beginTransaction();
        try {
            $email = checkEmail($request->email);
            $token = Str::random(60);

            $check_email = User::where('email', '=', $email);
            $check_email_reset = DB::select('select * from password_resets where email = ?', [$email]);

            if ($check_email->get()->count() == 0) {
                DB::rollBack();
                return ['status' => 'warning', 'message' => 'Email yang Anda masukkan tidak terdaftar.'];
            }

            if (count($check_email_reset) != 0) {
                DB::delete("DELETE FROM password_resets WHERE email = '" . $email . "'");
            }

            $input = DB::insert("INSERT INTO password_resets (email, token, created_at) VALUES ('" . $email . "', '" . bcrypt($token) . "', '" . Carbon::now() . "')");
            $check_email->first()->notify(new SendResetPasswordLinkEmailNotification($check_email->first(), $token));

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil mengirimkan link setel ulang kata sandi ke email Anda.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    protected function validator(array $data)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong',
            'email' => ':attribute yang Anda masukkan tidak valid',
        ];

        return Validator::make($data, [
            'email' => ['required', 'email'],
        ], $message);
    }
}
