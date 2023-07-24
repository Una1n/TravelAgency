<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Seeder;

class TravelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Travel::factory(5)
            ->has(Tour::factory(3))
            ->create();
    }
}
