<?php

namespace App\Models;

use App\Traits\SettingGroupTrait;
use Illuminate\Database\Eloquent\Model;

class SettingGroup extends Model
{
    use SettingGroupTrait;

    protected $fillable = ['name', 'slug', 'description', 'order'];

    public function getRouteKeyName()
    {
        return "slug";
    }
}
