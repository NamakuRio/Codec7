<?php

namespace App\Http\ViewComposers;

use App\Models\SettingGroup;
use Illuminate\View\View;

class SettingComposer
{
    public function compose(View $view)
    {
        $data = [];
        $settingGroups = SettingGroup::all();

        foreach($settingGroups as $settingGroup){
            $settings = $settingGroup->settings;

            foreach($settings as $setting){
                // if ($setting->name == 'app_logo' && $setting->value == null) {
                //     $data[$setting->name] = 'uploads/site/logo/default.png';
                // } else if ($setting->name == 'favicon' && $setting->value == null) {
                //     $data[$setting->name] = 'uploads/site/favicon/default.png';
                // } else if ($setting->name == 'app_name') {
                //     $data[$setting->name] = $setting->value;
                //     $data[$setting->name.'_small'] = substr($setting->value, 0, 2);
                // } else {
                    $data[$setting->name] = ($setting->value ?? $setting->default_value);
                // }
            }
        }

        $view->with($data);
    }
}
