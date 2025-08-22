<?php

namespace Fynkus\Tests\Space\Presentation\Rest\V1;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;

class E2EGetAllSpacesTest extends WebTestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost:8000',
            'timeout'  => 2.0,
        ]);
    }

    public function testGetAllSpacesOK(): void
    {
        $response = $this->client->request('GET', '/api/v1/space');

        $this->assertEquals(200, $response->getStatusCode());

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        foreach ($data as $space) {
            $this->assertArrayHasKey('uuid', $space);
            $this->assertArrayHasKey('name', $space);
        }
    }
}