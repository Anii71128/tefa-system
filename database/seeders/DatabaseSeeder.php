<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tefa.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['email' => 'guru@tefa.test'],
            [
                'name' => 'Guru TEFA',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );
        User::updateOrCreate(
            ['email' => 'siswa@tefa.test'],
            [
                'name' => 'Siswa TEFA',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
    }
}
