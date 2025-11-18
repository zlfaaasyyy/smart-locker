<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    protected $fillable = [
        'locker_number',
        'status'
    ];

    public function deposit()
    {
        return $this->hasMany(Deposit::class);
    }
}
