<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TimeSlot;

class TimeSlotsSeeder extends Seeder
{
    public function run()
    {
        $times = [
            ['07:00', '07.00', true],
            ['08:00', '08.00', true],
            ['09:00', '09.00', true],
            ['10:00', '10.00', true],
            ['11:00', '11.00', true],
            ['12:00', '12.00', true],
            ['13:00', '13.00', true],
            ['14:00', '14.00', true],
            ['15:00', '15.00', true],
            ['16:00', '16.00', true],
            ['17:00', '17.00', true],
            ['18:00', '18.00', true],
            ['19:00', '19.00', true],
            ['20:00', '20.00', true],
            ['21:00', '21.00', true],
            ['22:00', '22.00', true]
        ];
        
        foreach ($times as $time) {
            TimeSlot::create([
                'time' => $time[0],
                'display_text' => $time[1],
                'is_active' => $time[2]
            ]);
        }
    }
}