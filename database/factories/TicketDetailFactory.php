<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\TicketDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TicketDetail>
 */
class TicketDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'technical_info' => $this->faker->paragraph(),
            'payload' => [
                'env' => $this->faker->randomElement(['production', 'staging', 'development']),
                'error_code' => $this->faker->numerify('ERR-####'),
                'memory_usage' => $this->faker->numerify('## MB'),
            ],
        ];
    }
}
