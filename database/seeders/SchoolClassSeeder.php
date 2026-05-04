<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           $classes = ['1A', '1B', '2A', '2B', '3A', '3B'];

           foreach ($classes as $class) {
            SchoolClass::create([
                'name' => $class,
            ]);
           }
    }
}
