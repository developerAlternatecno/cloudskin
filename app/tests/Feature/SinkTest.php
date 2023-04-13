<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SinkTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_sinkCreationWrongEngineId_fail()
    {
        $response = $this->post('/api/sinks', [
            "user_id" => "1",
            "engine_id" => "id_that_doesnt_exist"
        ]);

        $response->assertStatus(500);
    }

    public function test_sinkCreationWrongUserId_fail()
    {
        # We create a Engine so we can get its id
        $engine = $this->post('/api/engines', [
            "temperature" => [
                "type" => "float",
                "unit" => "ºC"
            ],
            "humidity" => [
                "type"=> "float",
                "unit"=> "%"
            ],
            "string_test" => [
                "type"=> "string",
                "unit"=> "",
                "length"=> 7
            ]
        ]);

        $engine = json_decode($engine->getContent(), true);

        $response = $this->post('/api/sinks', [
            "user_id" => "id_that_doesnt_exist",
            "engine_id" => $engine['engine_id']
        ]);

        $response->assertStatus(500);
    }

    public function test_sinkCreation_pass()
    {
        # We create a Engine so we can get its id
        $engine = $this->post('/api/engines', [
            "temperature" => [
                "type" => "float",
                "unit" => "ºC"
            ],
            "humidity" => [
                "type"=> "float",
                "unit"=> "%"
            ],
            "string_test" => [
                "type"=> "string",
                "unit"=> "",
                "length"=> 7
            ]
        ]);

        $engine = json_decode($engine->getContent(), true);

        $response = $this->post('/api/sinks', [
            "user_id" => "1",
            "engine_id" => $engine['engine_id']
        ]);

        $response->assertStatus(200);
    }

    public function test_createDataReadSinkDoesntExist_fail()
    {
        # Trying to make the petition with a non existan sink id
        $response = $this->post('/api/sinks/id-sink-that-doesnt-exist', [
            "temperature" =>23.2,
            "humidity" => 70.2,
            "string_test" => "strÜng"
        ]);

        $response->assertStatus(404);
    }

    public function test_createDataReadWrongData_fail()
    {
        # We create a Engine so we can get its id
        $engine = $this->post('/api/engines', [
            "temperature" => [
                "type" => "float",
                "unit" => "ºC"
            ],
            "humidity" => [
                "type"=> "float",
                "unit"=> "%"
            ],
            "string_test" => [
                "type"=> "string",
                "unit"=> "",
                "length"=> 7
            ]
        ]);

        $engine = json_decode($engine->getContent(), true);
        $sink = $this->post('/api/sinks', [
            "user_id" => "1",
            "engine_id" => $engine['engine_id']
        ]);

        $url = json_decode($sink->getContent())->url;

        # We try to send a wrong data type
        $response = $this->post($url, [
            "wrond_data_field" =>23.2,
            "humidity" => 70.2,
            "string_test" => "strÜng"
        ]);

        $response->assertStatus(400);

        # We try to send a shorter data
        $response = $this->post($url, [
            "humidity" => 70.2,
            "string_test" => "strÜng"
        ]);

        $response->assertStatus(400);

        # We try to send data with wrong typing
        $response = $this->post($url, [
            "temperature" => "string_bad_type",
            "humidity" => 70.2,
            "string_test" => "strÜng"
        ]);

        $response->assertStatus(400);
    }

    public function test_createDataRead_pass()
    {
        # We create a Engine so we can get its id
        $engine = $this->post('/api/engines', [
            "temperature" => [
                "type" => "float",
                "unit" => "ºC"
            ],
            "humidity" => [
                "type"=> "float",
                "unit"=> "%"
            ],
            "string_test" => [
                "type"=> "string",
                "unit"=> "",
                "length"=> 7
            ]
        ]);

        $engine = json_decode($engine->getContent(), true);
        $sink = $this->post('/api/sinks', [
            "user_id" => "1",
            "engine_id" => $engine['engine_id']
        ]);

        $url = json_decode($sink->getContent())->url;

        # We send the correct data
        $response = $this->post($url, [
            "temperature" => 24.2,
            "humidity" => 70.2,
            "string_test" => "strÜng"
        ]);

        $response->assertStatus(200);
    }
}
