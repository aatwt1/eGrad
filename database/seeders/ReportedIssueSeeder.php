<?php

namespace Database\Seeders;

use App\Models\ReportedIssue;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportedIssueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 0)->get();
     

        ReportedIssue::factory()
            ->count(15)
            ->create([
                'user_id' => $users->random()->id,
            ]);
        
        $this->command->info('15 prijava problema je uspješno kreirano.');
    }
}