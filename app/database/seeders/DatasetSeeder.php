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


        # R0215
        # Dataset Padre
        DB::table('datasets')->insert(
            array(
                'id' => '3',
                'engine_id' => '3',
                'user_id' => '1',
                'parent_id' => Null,
                'name' => 'Dataset 3, prueba EXCEL, PADRE',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );
        # DATASET TER21
        DB::table('datasets')->insert(
            array(
                'id' => '4',
                'engine_id' => '3',
                'user_id' => '1',
                'parent_id' => '3',
                'name' => 'DATASET TER21',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # DATASET TER21.1
        DB::table('datasets')->insert(
            array(
                'id' => '5',
                'engine_id' => '4',
                'user_id' => '1',
                'parent_id' => '3',
                'name' => 'DATASET TER21.1',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # DATASET TER12
        DB::table('datasets')->insert(
            array(
                'id' => '6',
                'engine_id' => '5',
                'user_id' => '1',
                'parent_id' => '3',
                'name' => 'DATASET TER12',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

         # DATASET TER12.1
        DB::table('datasets')->insert(
            array(
                'id' => '7',
                'engine_id' => '6',
                'user_id' => '1',
                'parent_id' => '3',
                'name' => 'DATASET TER12.1',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );
    }
}
