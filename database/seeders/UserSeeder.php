<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First admin to create users with
        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $role = Role::query()->where('name', '=', 'admin')->first();
        $user->roles()->attach($role);
    }
}
