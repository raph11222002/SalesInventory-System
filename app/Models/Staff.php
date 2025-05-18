<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'admin_id',
        'name',
        'username',
        'password',
    ];

    protected $hidden = ['password', 'remember_token'];
}
