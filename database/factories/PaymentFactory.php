<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Spp;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'spp_id' => Spp::factory(),
            'amount' => $this->faker->randomElement([300000, 350000, 400000, 450000]),
            'status' => $this->faker->randomElement(['pending', 'paid']),
            'payment_date' => $this->faker->boolean() ? $this->faker->dateTime() : null,
            'payment_method' => $this->faker->randomElement(['cash', 'bank_transfer', 'check']),
            'reference_number' => $this->faker->regexify('[A-Z]{2}[0-9]{8}'),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'payment_date' => now(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_date' => null,
        ]);
    }
}
