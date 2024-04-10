<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatasetSeederR03 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # R0264
        # ENGINE TER11
        DB::table('engines')->insert(
            array(
                'id' => '11',
                'template'=> json_encode(
                    [
                        "Timestamp" =>[
                            "type" => "string",
                            "unit" => "",
                            "length" => 19
                        ],
                        "DIA" => [
                            "type" => "string",
                            "unit" => "",
                            "length" => null
                        ],
                        "%" => [
                            "type" => "float",
                            "unit" => "%",
                            "length" => null
                        ],
                        "°C" => [
                            "type" => "float",
                            "unit" => "°C",
                            "length" => null
                        ],
                        "uS/cm" => [
                            "type" => "float",
                            "unit" => "uS/cm",
                            "length" => null
                        ]
                    ]
                )
            )
        );

        # R0344
        # DATASET parent
        DB::table('datasets')->insert(
            array(
                'id' => '22',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => null,
                'name' => 'DATASET R0344',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # DATASET TER11
        DB::table('datasets')->insert(
            array(
                'id' => '23',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '22',
                'name' => 'DATASET TER11 R0344',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # DATASET TER11.1
        DB::table('datasets')->insert(
            array(
                'id' => '24',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '22',
                'name' => 'DATASET TER11.1 R0344',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # R0345
        # parent
        DB::table('datasets')->insert(
            array(
                'id' => '25',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => null,
                'name' => 'DATASET R0345',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # DATASET TER11
        DB::table('datasets')->insert(
            array(
                'id' => '26',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '25',
                'name' => 'DATASET TER11 R0345',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # DATASET TER11.1
        DB::table('datasets')->insert(
            array(
                'id' => '27',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '25',
                'name' => 'DATASET TER11.1 R0345',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # R0346
        # parent
        DB::table('datasets')->insert(
            array(
                'id' => '28',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => null,
                'name' => 'DATASET R0346',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );
        # DATASET TER11
        DB::table('datasets')->insert(
            array(
                'id' => '29',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '28',
                'name' => 'DATASET TER11 R0346',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # DATASET TER11.1
        DB::table('datasets')->insert(
            array(
                'id' => '30',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '28',
                'name' => 'DATASET TER11.1 R0346',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );



        # R0347
        # parent
        DB::table('datasets')->insert(
            array(
                'id' => '31',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => null,
                'name' => 'DATASET R0347',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );
        # DATASET TER11
        DB::table('datasets')->insert(
            array(
                'id' => '32',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '31',
                'name' => 'DATASET TER11 R0347',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # DATASET TER11.1
        DB::table('datasets')->insert(
            array(
                'id' => '33',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '31',
                'name' => 'DATASET TER11.1 R0347',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # R0348
        # parent
        DB::table('datasets')->insert(
            array(
                'id' => '34',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => null,
                'name' => 'DATASET R0348',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );
        # DATASET TER11
        DB::table('datasets')->insert(
            array(
                'id' => '35',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '34',
                'name' => 'DATASET TER11 R0348',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # DATASET TER11.1
        DB::table('datasets')->insert(
            array(
                'id' => '36',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '34',
                'name' => 'DATASET TER11.1 R0348',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # R0349
        # parent
        DB::table('datasets')->insert(
            array(
                'id' => '37',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => null,
                'name' => 'DATASET R0349',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );
        # DATASET TER11
        DB::table('datasets')->insert(
            array(
                'id' => '38',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '37',
                'name' => 'DATASET TER11 R0349',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # DATASET TER11.1
        DB::table('datasets')->insert(
            array(
                'id' => '39',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '37',
                'name' => 'DATASET TER11.1 R0349',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # R0350
        # parent
        DB::table('datasets')->insert(
            array(
                'id' => '40',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => null,
                'name' => 'DATASET R0350',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );
        # DATASET TER11
        DB::table('datasets')->insert(
            array(
                'id' => '41',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '40',
                'name' => 'DATASET TER11 R0350',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # DATASET TER11.1
        DB::table('datasets')->insert(
            array(
                'id' => '42',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '40',
                'name' => 'DATASET TER11.1 R0350',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );



        # R0351
        # parent
        DB::table('datasets')->insert(
            array(
                'id' => '43',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => null,
                'name' => 'DATASET R0351',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );
        # DATASET TER11
        DB::table('datasets')->insert(
            array(
                'id' => '44',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '43',
                'name' => 'DATASET TER11 R0351',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # DATASET TER11.1
        DB::table('datasets')->insert(
            array(
                'id' => '45',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '43',
                'name' => 'DATASET TER11.1 R0351',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # R0352
        # parent
        DB::table('datasets')->insert(
            array(
                'id' => '46',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => null,
                'name' => 'DATASET R0351',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );
        # DATASET TER11
        DB::table('datasets')->insert(
            array(
                'id' => '47',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '46',
                'name' => 'DATASET TER11 R0352',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # DATASET TER11.1
        DB::table('datasets')->insert(
            array(
                'id' => '48',
                'engine_id' => '11',
                'user_id' => '1',
                'parent_id' => '46',
                'name' => 'DATASET TER11.1 R0352',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );
    }
}
