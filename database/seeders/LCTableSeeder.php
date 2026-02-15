<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LocalCommunity;

class LCTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        LocalCommunity::factory()->count(12)->create();
    }
}
