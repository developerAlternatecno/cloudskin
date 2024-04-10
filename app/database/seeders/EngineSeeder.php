<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EngineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        id
//        updated_at
//        template
//        created_at
        DB::table('engines')->insert(
            array(
                'id' => '1',
                'template'=> json_encode(
                    [
                        "temperature" =>[
                            "type" => "float",
                            "unit" => "ºC",
                            "length" => null
                        ],
                        "humidity" => [
                            "type" => "float",
                            "unit" => "%",
                            "length" => null
                        ],
                        "string_test" =>[
                            "type" => "string",
                            "unit" => "",
                            "length" => 7
                        ]
                    ]
                )
            )
        );

        DB::table('engines')->insert(
            array(
                'id' => '2',
                'template'=> json_encode(
                    [
                        "max_temperature" =>[
                            "type" => "float",
                            "unit" => "ºC",
                            "length" => null
                        ],
                        "integer_test" => [
                            "type" => "test",
                            "unit" => "units",
                            "length" => null
                        ]
                    ]
                )
            )
        );

        # TER21
        DB::table('engines')->insert(
            array(
                'id' => '3',
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

        # TER21.1
        DB::table('engines')->insert(
            array(
                'id' => '4',
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

        # TER12
        DB::table('engines')->insert(
            array(
                'id' => '5',
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

        # TER12.1
        DB::table('engines')->insert(
            array(
                'id' => '6',
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
    }
}
