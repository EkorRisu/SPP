<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user guru
        User::create([
            'name' => 'Guru SPP',
            'email' => 'guru@example.com',
            'password' => Hash::make('password'),
            'role' => 'guru', // Pastikan kolom "role" ada di database
        ]);

        // Buat user murid
        User::create([
            'name' => 'Murid SPP',
            'email' => 'murid@example.com',
            'password' => Hash::make('password'),
            'role' => 'murid',
        ]);
    }
}
