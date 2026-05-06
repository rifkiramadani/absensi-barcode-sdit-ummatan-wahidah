<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),

            'gender' => $this->faker->randomElement(['L', 'P']),

            'birth_place' => $this->faker->city(),

            'birth_date' => $this->faker->date('Y-m-d', '2018-12-31'), // anak SD

            'nik' => $this->faker->unique()->numerify('################'), // 16 digit

            'entry_year' => $this->faker->numberBetween(2018, 2024),

            'photo' => null,

            'school_class_id' => SchoolClass::inRandomOrder()->first()->id ?? 1,

            // UID RFID realistis (hex)
            'rfid_uid' => strtoupper(bin2hex(random_bytes(4))),
        ];
    }
}
