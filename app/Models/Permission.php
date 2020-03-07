<?php

namespace App\Models;

use App\Traits\PermissionTrait;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use PermissionTrait;

    protected $fillable = ['name', 'guard_name'];
}
