<?php

namespace App\Traits;

use App\Models\Icon;
use App\Models\Setting;

trait SettingGroupTrait
{
    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function icon()
    {
        return $this->belongsTo(Icon::class);
    }
}
