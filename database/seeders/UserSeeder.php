<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create user with the necessary token

        DB::table('users')->insert([
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => Hash::make('password'),
            'token' => 'vg@123'
        ]);
    }
}
