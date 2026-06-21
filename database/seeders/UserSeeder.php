<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'      => 'Admin Klinik',
            'email'     => 'admin@klinik.test',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'jam_masuk' => '08:00:00',
        ]);

        $karyawan = [
            ['name' => 'Budi Santoso',   'email' => 'budi@klinik.test',   'jam_masuk' => '08:00:00'],
            ['name' => 'Sari Wulandari', 'email' => 'sari@klinik.test',   'jam_masuk' => '08:30:00'],
            ['name' => 'Rina Astuti',    'email' => 'rina@klinik.test',   'jam_masuk' => '09:00:00'],
        ];

        foreach ($karyawan as $row) {
            User::create([
                'name'      => $row['name'],
                'email'     => $row['email'],
                'password'  => Hash::make('password'),
                'role'      => 'karyawan',
                'jam_masuk' => $row['jam_masuk'],
            ]);
        }
    }
}
