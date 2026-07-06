<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolClassFactory extends Factory
{
    protected $model = SchoolClass::class;

    public function definition(): array
    {
        $grades = ['X', 'XI', 'XII'];
        $programs = ['MIPA', 'IPS', 'BAHASA'];
        $numbers = [1, 2, 3, 4];

        $grade = $this->faker->randomElement($grades);
        $program = $this->faker->randomElement($programs);
        $number = $this->faker->randomElement($numbers);

        $code = "{$grade}_{$program}_{$number}";
        $name = "{$grade} {$program} {$number}";

        return [
            'code' => $code,
            'name' => $name,
            'total_students' => 0,
        ];
    }
}
