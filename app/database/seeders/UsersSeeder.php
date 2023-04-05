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
                'name' => 'admin',
                'password' => '$2y$10$phkvVr2yntv/seNLae6mvOlDLh7LFBHaPl2.t52rPuIBnYM9I8.j.', //123456
                'email' => 'admin@admin.com',
                //'rol' => 'admin'
            )
        );
    }
}
