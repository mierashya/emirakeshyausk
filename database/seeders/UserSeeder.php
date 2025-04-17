<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'balance' => 1000.00,
                'remember_token' => Str::random(10),
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'bankmini',
                'email' => 'bankmini@example.com',
                'password' => Hash::make('bankmini123'),
                'role' => 'bank_mini',
                'balance' => 500.00,
                'remember_token' => Str::random(10),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'siswa',
                'email' => 'siswa@example.com',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
                'balance' => 200.00,
                'remember_token' => Str::random(10),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
