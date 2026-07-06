<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Spp;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create officer user
        User::create([
            'name' => 'Officer',
            'email' => 'officer@example.com',
            'password' => Hash::make('password'),
            'role' => 'officer',
        ]);

        // Create classes
        $classes = SchoolClass::factory(9)->create();

        // Create SPP
        $activeSpp = Spp::factory()->active()->create([
            'name' => 'SPP Tahun 2024',
            'academic_year' => '2023/2024',
            'amount' => 400000,
        ]);

        Spp::factory(3)->create();

        // Create students (30 per class)
        Student::factory(270)->sequence(function ($sequence) use ($classes) {
            $classIndex = intval($sequence->index / 30);
            return [
                'class_id' => $classes[$classIndex]->id ?? $classes[0]->id,
            ];
        })->create();

        // Create payments
        Student::all()->each(function ($student) use ($activeSpp) {
            // Each student has 12 SPP records (one for each month)
            for ($i = 1; $i <= 12; $i++) {
                $isPaid = rand(0, 100) > 40; // 60% paid, 40% pending

                Payment::factory()
                    ->when($isPaid, fn ($factory) => $factory->paid())
                    ->when(!$isPaid, fn ($factory) => $factory->pending())
                    ->create([
                        'student_id' => $student->id,
                        'spp_id' => $activeSpp->id,
                    ]);
            }
        });

        echo "Database seeded successfully!\n";
        echo "Admin: admin@example.com / password\n";
        echo "Officer: officer@example.com / password\n";
    }
}
