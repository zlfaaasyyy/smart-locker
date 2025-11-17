<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'user_name',
        'user_phone',
        'item_description',
        'locker_id',
        'pickup_code',
        'status'
    ];

    public function locker()
    {
        return $this->belongsTo(Locker::class);
    }
}

