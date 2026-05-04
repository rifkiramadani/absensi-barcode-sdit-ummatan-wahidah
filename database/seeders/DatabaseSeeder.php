<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\SettingSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SettingSeeder::class);

        User::factory(1)->create();

        User::factory()->create([
            'name' => 'admin sdituw',
            'email' => 'admin@example.com',
        ]);
    }
}
