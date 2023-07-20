<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatasetSeederR0264 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # R0264
        # ENGINE TER21
        DB::table('engines')->insert(
            array(
                'id' => '9',
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
                        "kPa" => [
                            "type" => "float",
                            "unit" => "kPa",
                            "length" => null
                        ],
                        "°C" => [
                            "type" => "float",
                            "unit" => "°C",
                            "length" => null
                        ]
                    ]
                )
            )
        );

        # DATASET TER21
        DB::table('datasets')->insert(
            array(
                'id' => '18',
                'engine_id' => '9',
                'user_id' => '1',
                'parent_id' => '13',
                'name' => 'DATASET TER21 R0264',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # ENGINE MPS-6
        DB::table('engines')->insert(
            array(
                'id' => '10',
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
                        "kPa" => [
                            "type" => "float",
                            "unit" => "kPa",
                            "length" => null
                        ],
                        "°C" => [
                            "type" => "float",
                            "unit" => "°C",
                            "length" => null
                        ]
                    ]
                )
            )
        );

        # DATASET TER21
        DB::table('datasets')->insert(
            array(
                'id' => '19',
                'engine_id' => '10',
                'user_id' => '1',
                'parent_id' => '13',
                'name' => 'DATASET TER21 R0264',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );


        # ENGINE TER12
        DB::table('engines')->insert(
            array(
                'id' => '7',
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

        # DATASET TER12
        DB::table('datasets')->insert(
            array(
                'id' => '20',
                'engine_id' => '7',
                'user_id' => '1',
                'parent_id' => '13',
                'name' => 'DATASET TER12 R0264',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

        # ENGINE TER12.1
        DB::table('engines')->insert(
            array(
                'id' => '8',
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

        # DATASET TER12.1
        DB::table('datasets')->insert(
            array(
                'id' => '21',
                'engine_id' => '8',
                'user_id' => '1',
                'parent_id' => '13',
                'name' => 'DATASET TER12 R0264',
                'type' => 'rental',
                'price' => 5,
                'license' => 'european',
                'description' => 'This is a description 2',
                'is_geolocated' => false
            )
        );

    }
}
