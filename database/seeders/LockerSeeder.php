<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LockerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 1; $i <= 12; $i++) {
            \App\Models\Locker::create([
                'locker_number' => 'L-' . $i,
                'status' => 'available'
            ]);
        }
    }

}
