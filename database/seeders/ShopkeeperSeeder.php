<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shopkeeper;
use Illuminate\Support\Facades\Hash;

class ShopkeeperSeeder extends Seeder
{
    public function run(): void
    {
        Shopkeeper::create([
            'UserName' => 'admin',
            'Password' => Hash::make('password'),
        ]);
    }
} 