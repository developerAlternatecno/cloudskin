<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('datasets')->insert(
            array(
                'id' => '1',
                'engine_id' => '1',
                'user_id' => '1',
                'name' => 'Dataset 1',
                'type' => 'buyout',
                'price' => 10,
                'license' => 'national',
                'description' => 'This is a description',
                'is_geolocated' => true
            )
        );

        DB::table('datasets')->insert(
            array(
                'id' => '2',
                'engine_id' => '2',
                'user_id' => '1',
                'name' => 'Dataset 2',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );
    }
}
