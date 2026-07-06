<?php

namespace Database\Factories;

use App\Models\Spp;
use Illuminate\Database\Eloquent\Factories\Factory;

class SppFactory extends Factory
{
    protected $model = Spp::class;

    public function definition(): array
    {
        $year = $this->faker->year();
        $nextYear = $year + 1;

        return [
            'name' => "SPP Tahun {$year}",
            'academic_year' => "{$year}/{$nextYear}",
            'amount' => $this->faker->randomElement([300000, 350000, 400000, 450000]),
            'is_active' => false,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }
}
