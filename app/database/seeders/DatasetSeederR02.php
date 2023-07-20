<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatasetSeederR02 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # R0216
        # Dataset Padre
        DB::table('datasets')->insert(
            array(
                'id' => '8',
                'engine_id' => '3',
                'user_id' => '1',
                'parent_id' => Null,
                'name' => 'Dataset R0216',
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
                'id' => '9',
                'engine_id' => '3',
                'user_id' => '1',
                'parent_id' => '8',
                'name' => 'DATASET TER21 R0216',
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
                'id' => '10',
                'engine_id' => '4',
                'user_id' => '1',
                'parent_id' => '8',
                'name' => 'DATASET TER21.1 R0216',
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
                'id' => '11',
                'engine_id' => '5',
                'user_id' => '1',
                'parent_id' => '8',
                'name' => 'DATASET TER12 R0216',
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
                'id' => '12',
                'engine_id' => '6',
                'user_id' => '1',
                'parent_id' => '8',
                'name' => 'DATASET TER12.1 R0216',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # R0217
        # Dataset Padre
        DB::table('datasets')->insert(
            array(
                'id' => '13',
                'engine_id' => '3',
                'user_id' => '1',
                'parent_id' => Null,
                'name' => 'Dataset R0217',
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
                'id' => '14',
                'engine_id' => '3',
                'user_id' => '1',
                'parent_id' => '13',
                'name' => 'DATASET TER21 R0217',
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
                'id' => '15',
                'engine_id' => '4',
                'user_id' => '1',
                'parent_id' => '13',
                'name' => 'DATASET TER21.1 R0217',
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
                'id' => '16',
                'engine_id' => '5',
                'user_id' => '1',
                'parent_id' => '13',
                'name' => 'DATASET TER12 R0217',
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
                'id' => '17',
                'engine_id' => '6',
                'user_id' => '1',
                'parent_id' => '13',
                'name' => 'DATASET TER12.1 R0217',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );
    }
}
