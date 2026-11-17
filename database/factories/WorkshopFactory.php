<?php

namespace Database\Factories;

use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Workshop>
 */
class WorkshopFactory extends Factory
{
    protected $model = Workshop::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title) . '-' . fake()->unique()->randomNumber(4),
            'description' => fake()->paragraphs(2, true),
            'speaker' => fake()->name() . ', S.Kom., M.T.',
            'location' => fake()->randomElement(['Lab Komputer 1 SMK PESAT', 'Lab RPL SMK PESAT', 'Auditorium Lantai 3 SMK PESAT', 'Ruang Seminar Cyber']),
            'starts_at' => fake()->dateTimeBetween('+1 days', '+30 days'),
            'price' => fake()->randomElement([50000, 75000, 100000, 150000, 200000]),
            'quota' => fake()->numberBetween(20, 50),
        ];
    }
}