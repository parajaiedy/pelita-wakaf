<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil seeder aset wakaf
        $this->call(AsetWakafSeeder::class);

        // Buat Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@bpn.go.id'],
            [
                'name' => 'Admin BPN Parepare',
                'password' => Hash::make('admin123'), // Password admin
            ]
        );
    }
}