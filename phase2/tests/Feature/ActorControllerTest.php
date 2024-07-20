<?php

namespace Tests\Feature;

use Exception;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ActorControllerTest extends TestCase
{
    private $hasFailed = false;

    protected function tearDown(): void
    {
        parent::tearDown();
        if ($this->hasFailed) {
            echo "The test has failed!\n";
        } else {
            echo "API testing has been performed successfully\n";
        }
    }

    /**
     * @throws Exception
     */
    public function testGetActorsByValidBirthdate()
    {
        Http::fake([
            'online-movie-database.p.rapidapi.com/actors/v2/get-born-today*' => Http::response([
                'data' => [
                    'bornToday' => [
                        'edges' => [
                            ['node' => ['id' => 'nm0001']],
                            ['node' => ['id' => 'nm0002']]
                        ]
                    ]
                ]
            ], 200),
            'online-movie-database.p.rapidapi.com/actors/v2/get-bio*' => Http::response([
                'data' => [
                    'name' => [
                        'nameText' => ['text' => 'Actor Name'],
                        'primaryImage' => ['url' => 'https://example.com/image.jpg']
                    ]
                ]
            ], 200)
        ]);

        try {
            $response = $this->get('/get-born-today/09-25');

            $response->assertStatus(200);
            $response->assertJsonStructure([
                '*' => ['name', 'imageUrl']
            ]);
        } catch (Exception $e) {
            $this->hasFailed = true;
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function testGetActorsByInvalidBirthdate()
    {
        try {
            $response = $this->get('/get-born-today/22-22');

            $response->assertStatus(422);
            $response->assertJson([
                'status' => 'failed',
                'message' => 'Validation failed',
                'errors' => [
                    "date_of_birth" => ["The date of birth field format is invalid."]
                ]
            ]);
        } catch (Exception $e) {
            $this->hasFailed = true;
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function testApiFailure()
    {
        Http::fake([
            'online-movie-database.p.rapidapi.com/*' => Http::response(null, 500)
        ]);

        try{
            $response = $this->get('/get-born-today/10-10');

            $response->assertStatus(500);
            $response->assertJson([
                'status' => 'failed',
                'message' => 'Failed to fetch data'
            ]);
        }catch(Exception $e){
            $this->hasFailed = true;
            throw $e;
        }
    }
}
