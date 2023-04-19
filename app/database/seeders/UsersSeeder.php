<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert(
            array(
                'id' => '1',
                'name' => 'Admin',
                'password' => '$2y$10$phkvVr2yntv/seNLae6mvOlDLh7LFBHaPl2.t52rPuIBnYM9I8.j.', //123456
                'email' => 'Admin@Admin.com',
                'nif' => '23437876E',
                'nationality' => 'Spain',
                //'rol' => 'Admin'
            )
        );

        DB::table('users')->insert(
            array(
                'id' => '2',
                'name' => 'User',
                'password' => '$2y$10$phkvVr2yntv/seNLae6mvOlDLh7LFBHaPl2.t52rPuIBnYM9I8.j.', //123456
                'email' => 'user@user.com',
                'nif' => '23437116E',
                'nationality' => 'Spain',
                //'rol' => 'User'
            )
        );

        User::find(1)->assignRole('Admin');
        User::find(2)->assignRole('User');
    }
}
