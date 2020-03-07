<?php

namespace App\Models;

use App\Traits\SettingTrait;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use SettingTrait;

    protected $fillable = ['name', 'value' , 'default_value', 'type', 'comment', 'required', 'order'];
}
