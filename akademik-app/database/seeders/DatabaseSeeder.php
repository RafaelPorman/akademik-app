<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([
            ['name' => 'Admin Kampus', 'email' => 'admin@kampus.ac.id', 'role' => 'admin'],
            ['name' => 'Dosen Kampus', 'email' => 'dosen@kampus.ac.id', 'role' => 'dosen'],
            ['name' => 'Mahasiswa Kampus', 'email' => 'mahasiswa@kampus.ac.id', 'role' => 'mahasiswa'],
        ] as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => 'password',
                    'role' => $account['role'],
                ],
            );
        }
    }
}
