<?php

namespace App\Services\Auth;

use App\Events\UserLoginEvent;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginService
{
    public function web(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        DB::beginTransaction();
        try {
            if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
                DB::rollback();
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            if ($this->attemptLogin($request)) {
                $request->session()->regenerate();

                $this->clearLoginAttempts($request);

                $status_user = $this->checkStatusUser();
                if ($status_user['status'] != 'success') {
                    DB::rollback();

                    $this->guard()->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return $status_user;
                }

                $user = auth()->user();

                event(new UserLoginEvent($user));

                $login_destination = $user->roles->first()->login_destination;

                DB::commit();
                return ['status' => 'success', 'message' => 'Berhasil masuk.', 'login_destination' => $login_destination];
            }

            $this->incrementLoginAttempts($request);

            DB::rollback();
            return ['status' => 'error', 'message' => 'Nama Pengguna atau Kata Sandi yang Anda masukkan salah.'];
        } catch (Exception $e) {
            DB::rollback();
            $this->logout($request);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return ['status' => 'success', 'message' => 'Berhasil keluar.'];
    }

    public function username()
    {
        return 'username';
    }

    protected function checkStatusUser()
    {
        $user = auth()->user();
        $return = [];

        switch ($user->status) {
            case 0:
                $return =  ['status' => 'warning', 'message' => 'Akun Anda belum aktif.'];
                break;
            case 1:
                $return =  ['status' => 'success', 'message' => 'Anda dapat masuk.'];
                break;
            case 2:
                $return =  ['status' => 'error', 'message' => 'Akun Anda diblokir.'];
                break;
            default:
                $return =  ['status' => 'error', 'message' => 'Status Anda tidak terdaftar.'];
        }

        return $return;
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return $request->only('username', 'password');
    }

    protected function guard()
    {
        return Auth::guard();
    }

    protected function validator(array $data, $type = 'web')
    {
        if ($type == 'web') {
            $rules = [
                'username' => ['required', 'string'],
                'password' => ['required', 'string'],
            ];

            $messages = [
                'required' => ':attribute tidak boleh kosong',
                'string' => ':attribute harus berupa String'
            ];
        } else {
            $rules = [];
            $messages = [];
        }

        return Validator::make($data, $rules, $messages);
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $this->maxAttempts()
        );
    }

    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttleKey($request), $this->decayMinutes() * 60
        );
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            $this->username() => [Lang::get('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ])],
        ])->status(Response::HTTP_TOO_MANY_REQUESTS);
    }

    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    protected function fireLockoutEvent(Request $request)
    {
        event(new Lockout($request));
    }

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input($this->username())).'|'.$request->ip();
    }

    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    protected function maxAttempts()
    {
        return property_exists($this, 'maxAttempts') ? $this->maxAttempts : 5;
    }

    protected function decayMinutes()
    {
        return property_exists($this, 'decayMinutes') ? $this->decayMinutes : 1;
    }
}
