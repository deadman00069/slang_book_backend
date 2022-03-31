<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Users extends Model
{
    use HasApiTokens,HasFactory, Notifiable,HasRoles;

    protected $fillable = ['name', 'email', 'password', 'phone_no', 'fcm_token'];

    protected $hidden = ['password'];
}
