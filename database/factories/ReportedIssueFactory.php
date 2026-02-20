<?php

namespace Database\Factories;

use App\Models\ReportedIssue;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportedIssueFactory extends Factory
{
    protected $model = ReportedIssue::class;

    public function definition(): array
    {
        $statuses = ['pending', 'in_progress', 'resolved'];
        
        $locations = [
            'Centar, Trg 500, Atl',
            'Stambeno naselje AB, Atl',
            'MZ Vrelo, Atl',
            'Naselje Vrelo, Atl',
            'MZ Dan, Atl',
            'Ulica Proljetnih Pčela, Atl',
        ];

        return [
            'title' => $this->faker->randomElement([
                'Problemi sa infrastrukturom',
                'Nedostatak javne rasvjete',
                'Divlja deponija',
                'Oštećen put',
                'Komunalni problem',
                'Prokišnjavanje vodovoda',
                'Buka u naselju',
                'Nekontrolisano odlaganje otpada',
                'Problemi sa kanalizacijom',
                'Napušteno vozilo',
            ]),
            'description' => $this->faker->paragraphs(2, true),
            'location' => $this->faker->randomElement($locations),
            'status' => $this->faker->randomElement($statuses),
            'attachments' => null, 
            'user_id' => User::where('role', 0)->inRandomOrder()->first()->id ?? User::factory(),
            'local_community_id' => $this->faker->numberBetween(1, 12),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'updated_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
        ]);
    }

}