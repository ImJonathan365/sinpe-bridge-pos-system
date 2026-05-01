<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;

class BaseUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pos.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12341234'),
                'role' => UserRole::ADMINISTRATOR,
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'cajero@pos.local'],
            [
                'name' => 'Cajero',
                'password' => Hash::make('12341234'),
                'role' => UserRole::CASHIER,
                'is_active' => true,
            ]
        );
    }
}
