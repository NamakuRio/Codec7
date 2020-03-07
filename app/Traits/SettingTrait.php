<?php

namespace App\Traits;

use App\Models\SettingGroup;

trait SettingTrait
{
    public function settingGroup()
    {
        return $this->belongsTo(SettingGroup::class);
    }
}
