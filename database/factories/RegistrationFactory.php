<?php

namespace Database\Factories;

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'paid', 'failed', 'expired']);
        $isPaid = $status === 'paid';

        return [
            'user_id' => User::factory(),
            'workshop_id' => Workshop::factory(),
            'order_id' => 'WTX-' . fake()->unique()->numerify('#####') . '-' . time(),
            'status' => $status,
            'snap_token' => 'dummy-snap-token-' . Str::random(20),
            'ticket_code' => $isPaid ? 'WTX-' . strtoupper(Str::random(8)) : null,
            'paid_at' => $isPaid ? now()->subMinutes(fake()->numberBetween(10, 1440)) : null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn () => [
            'status' => 'paid',
            'ticket_code' => 'WTX-' . strtoupper(Str::random(8)),
            'paid_at' => now(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => 'pending',
            'ticket_code' => null,
            'paid_at' => null,
        ]);
    }
}