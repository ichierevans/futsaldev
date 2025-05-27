<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Field;
use Illuminate\Support\Facades\DB;

class FieldSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data yang ada dengan cara yang aman
        DB::table('fields')->delete();

        $fields = [
            [
                'name' => 'Lapangan 1',
                'description' => 'Lapangan futsal dengan rumput sintetis berkualitas tinggi',
                'price_per_hour' => 100000,
                'status' => 'available',
            ],
            [
                'name' => 'Lapangan 2',
                'description' => 'Lapangan futsal dengan rumput sintetis berkualitas tinggi',
                'price_per_hour' => 100000,
                'status' => 'available',
            ],
            [
                'name' => 'Lapangan 3',
                'description' => 'Lapangan futsal dengan rumput sintetis berkualitas tinggi',
                'price_per_hour' => 100000,
                'status' => 'available',
            ],
            [
                'name' => 'Lapangan 4',
                'description' => 'Lapangan futsal dengan rumput sintetis berkualitas tinggi',
                'price_per_hour' => 100000,
                'status' => 'available',
            ],
            [
                'name' => 'Lapangan 5',
                'description' => 'Lapangan futsal dengan rumput sintetis berkualitas tinggi',
                'price_per_hour' => 100000,
                'status' => 'available',
            ],
            [
                'name' => 'Lapangan 6',
                'description' => 'Lapangan futsal dengan rumput sintetis berkualitas tinggi',
                'price_per_hour' => 100000,
                'status' => 'available',
            ],
            [
                'name' => 'Lapangan 7',
                'description' => 'Lapangan futsal dengan rumput sintetis berkualitas tinggi',
                'price_per_hour' => 100000,
                'status' => 'available',
            ],
        ];

        foreach ($fields as $field) {
            Field::create($field);
        }
    }
} 