<?php

namespace Fynkus\Tests\Reservation\Presentation\Rest\V1;


use Faker\Factory as FakerFactory;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;

class E2EPostReservationTest extends WebTestCase
{
    private $faker;
    private Client $client;

    protected function setUp(): void
    {
        $this->faker = FakerFactory::create();
        $this->client = new Client([
            'base_uri' => 'http://localhost:8000',
            'timeout'  => 2.0,
        ]);
    }

    public function testPostReservationOK(): void
    {
        $spaceResponse = $this->client->request('GET', '/api/v1/space');
        $spaceBody = (string) $spaceResponse->getBody();
        $spaceData = json_decode($spaceBody, true);
        $payload = [
                        'spaceUuid' => $spaceData[0]["uuid"],
                        'date' => '21/07/2025',
                        'slots' => [
                            ['hour' => 9, 'status' => 'reserved'],
                            ['hour' => 10, 'status' => 'reserved']
                        ]
                    ];

        $response = $this->client->request('POST', '/api/v1/reservation', [
            'json' => $payload
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        foreach ($data as $reservation) {
            $this->assertArrayHasKey('uuid', $reservation);
            $this->assertNotEmpty($reservation['uuid']);
        }
    }
}
// 