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
    }
}
