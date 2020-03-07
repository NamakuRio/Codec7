<?php

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

if (!function_exists('settingApp')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function settingApp($name)
    {
        $setting = Setting::where('name', $name)->first();

        if(!$setting){
            return null;
        }

        return ($setting->value ?? $setting->default_value);
    }
}
