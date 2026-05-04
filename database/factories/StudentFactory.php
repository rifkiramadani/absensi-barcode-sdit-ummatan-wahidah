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
            'school_class_id' => SchoolClass::inRandomOrder()->first()->id ?? 1,
            'rfid_uid' => strtoupper($this->faker->unique()->bothify('??##??##')),
        ];
    }
}
