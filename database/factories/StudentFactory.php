<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'nisn' => $this->faker->unique()->numerify('##############'),
            'name' => $this->faker->name(),
            'class_id' => SchoolClass::factory(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'birth_date' => $this->faker->dateTimeBetween('-18 years', '-15 years'),
            'nis' => $this->faker->unique()->numerify('##########'),
        ];
    }
}
