<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name' => 'admin', 'email' => 'admin@gmail.com', 'password' => bcrypt('password')])->assignRole('admin');
        User::create(['name' => 'user1', 'email' => 'user1@gmail.com', 'password' => bcrypt('password')])->assignRole('user');
           
    }
}
