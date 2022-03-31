<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'user_id',
        'subscription_start_timestamp',
        'subscription_end_timestamp',
        'is_completed',
    ];
}
