<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatareadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('datareads')->insert(
            array(
                'id' => '1',
                'dataset_id' => '1',
                'latitude' => 37.674349,
                'longitude' => -1.072201,
                'data' => json_encode(
                    [
                        "temperature" => 20.5,
                        "humidity" => 50.5,
                        "string_test" => "string"
                    ]
                )

            )
        );
        DB::table('datareads')->insert(
            array(
                'id' => '2',
                'dataset_id' => '1',
                'latitude' => 37.674349,
                'longitude' => -1.072201,
                'data' => json_encode(
                    [
                        "temperature" => 22.5,
                        "humidity" => 30.5,
                        "string_test" => "string2"
                    ]
                )

            )
        );
        DB::table('datareads')->insert(
            array(
                'id' => '3',
                'dataset_id' => '1',
                'latitude' => 37.674349,
                'longitude' => -1.072201,
                'data' => json_encode(
                    [
                        "temperature" => 24.5,
                        "humidity" => 40.5,
                        "string_test" => "string3"
                    ]
                )
            )
        );
        DB::table('datareads')->insert(
            array(
                'id' => '4',
                'dataset_id' => '1',
                'latitude' => 37.674349,
                'longitude' => -1.072201,
                'data' => json_encode(
                    [
                        "temperature" => 28.2,
                        "humidity" => 55.5,
                        "string_test" => "string4"
                    ]
                )
            )
        );
        DB::table('datareads')->insert(
            array(
                'id' => '5',
                'dataset_id' => '1',
                'latitude' => 37.917115,
                'longitude' => -1.082743,
                'data' => json_encode(
                    [
                        "temperature" => 10.2,
                        "humidity" => 10.53,
                        "string_test" => "string5"
                    ]
                )
            )
        );
        DB::table('datareads')->insert(
            array(
                'id' => '6',
                'dataset_id' => '1',
                'latitude' => 37.917115,
                'longitude' => -1.082743,
                'data' => json_encode(
                    [
                        "temperature" => 14.7,
                        "humidity" => 12.2,
                        "string_test" => "string6"
                    ]
                )
            )
        );
        DB::table('datareads')->insert(
            array(
                'id' => '7',
                'dataset_id' => '1',
                'latitude' => 37.917115,
                'longitude' => -1.082743,
                'data' => json_encode(
                    [
                        "temperature" => 16.3,
                        "humidity" => 32.1,
                        "string_test" => "string7"
                    ]
                )
            )
        );
        DB::table('datareads')->insert(
            array(
                'id' => '8',
                'dataset_id' => '2',
                'latitude' => null,
                'longitude' => null,
                'data' => json_encode(
                    [
                        "max_temperature" => 30.3,
                        "integer_test" => 72,
                    ]
                )
            )
        );
        DB::table('datareads')->insert(
            array(
                'id' => '9',
                'dataset_id' => '2',
                'latitude' => null,
                'longitude' => null,
                'data' => json_encode(
                    [
                        "max_temperature" => 33.3,
                        "integer_test" => 27,
                    ]
                )
            )
        );
        DB::table('datareads')->insert(
            array(
                'id' => '10',
                'dataset_id' => '2',
                'latitude' => null,
                'longitude' => null,
                'data' => json_encode(
                    [
                        "max_temperature" => 36.3,
                        "integer_test" => 10,
                    ]
                )
            )
        );
        DB::table('datareads')->insert(
            array(
                'id' => '11',
                'dataset_id' => '2',
                'latitude' => null,
                'longitude' => null,
                'data' => json_encode(
                    [
                        "max_temperature" => 27.3,
                        "integer_test" => 11,
                    ]
                )
            )
        );
        DB::table('datareads')->insert(
            array(
                'id' => '12',
                'dataset_id' => '2',
                'latitude' => null,
                'longitude' => null,
                'data' => json_encode(
                    [
                        "max_temperature" => 22.9,
                        "integer_test" => 83,
                    ]
                )
            )
        );
    }
}
