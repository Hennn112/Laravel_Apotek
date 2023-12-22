<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KasirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Cashier',
            'email' => 'kasirapotek@gmail.com',
            'password' => Hash::make('kasir'),
            'role' => 'cashier',
        ]);
    }
}
