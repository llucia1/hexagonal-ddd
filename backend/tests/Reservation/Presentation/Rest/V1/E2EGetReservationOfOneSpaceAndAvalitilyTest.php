<?php

namespace Fynkus\Tests\Reservation\Presentation\Rest\V1;


use Faker\Factory as FakerFactory;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;

class E2EGetReservationOfOneSpaceAndAvalitilyTest extends WebTestCase
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

    public function testGetReservationOfOneSpaceAndDayOK(): void
    {

        $spaceResponse = $this->client->request('GET', '/api/v1/space');
        $spaceBody = (string) $spaceResponse->getBody();
        $spaceData = json_decode($spaceBody, true);
        $spaceUuid = $spaceData[0]['uuid'];


        $date = '21/07/2025';

        $response = $this->client->request(
            'GET',
            "/api/v1/reservation/space/{$spaceUuid}/vailability",
            [
                'query' => ['date' => $date]
            ]
        );

        $this->assertEquals(200, $response->getStatusCode());

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertIsArray($data);

        foreach ($data as $reservation) {
            $this->assertArrayHasKey('uuid', $reservation);
            $this->assertNotEmpty($reservation['uuid']);

            $this->assertArrayHasKey('date', $reservation);
            $this->assertEquals(date('Y-m-d', strtotime(str_replace('/', '-', $date))), $reservation['date']);

            $this->assertArrayHasKey('space', $reservation);
            $this->assertNotEmpty($reservation['space']);

            $this->assertArrayHasKey('Hour', $reservation);
            $this->assertIsInt($reservation['Hour']);

            $this->assertArrayHasKey('status', $reservation);
            $this->assertNotEmpty($reservation['status']);
        }
    }
}
//  php bin/phpunit tests/Reservation/Presentation/Rest/V1/E2EGetReservationOfOneSpaceAndAvalitilyTest.php