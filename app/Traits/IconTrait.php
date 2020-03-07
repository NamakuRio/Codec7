<?php

namespace App\Traits;

use App\Models\SettingGroup;

trait IconTrait
{
    public function settingGroups()
    {
        return $this->hasMany(SettingGroup::class);
    }
}
