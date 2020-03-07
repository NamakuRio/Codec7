<?php

namespace App\Models;

use App\Traits\RoleTrait;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use RoleTrait;

    protected $fillable = ['name', 'default_user', 'login_destination'];
}
