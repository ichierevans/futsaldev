<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // FieldsSeeder.php
public function run()
{
    for ($i = 1; $i <= 7; $i++) {
        Field::create([
            'name' => 'Lapangan ' . $i,
            'description' => 'Lapangan futsal berkualitas dengan fasilitas lengkap',
            'image' => 'fields/field' . $i . '.jpg'
        ]);
    }
}
}
