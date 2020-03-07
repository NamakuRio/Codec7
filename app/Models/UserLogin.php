<?php

namespace App\Models;

use App\Traits\UserLoginTrait;
use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    use UserLoginTrait;

    protected $fillable = ['ip_address', 'device', 'platform', 'browser', 'user_agent'];
}
