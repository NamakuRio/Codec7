<?php

namespace App\Models;

use App\Traits\IconTrait;
use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    use IconTrait;

    protected $fillable = ['icon'];
}
