<?php

namespace App\Tests\Service;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Tweet;
use DateTime;

class TweetsTest extends ApiTestCase
{
    public function testGetCollectionReturnsValidData(): void
    {
        $response = static::createClient()->request('GET', '/api/tweets',
            [ "headers" => ["Accept: application/json"]]
        );

        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceCollectionJsonSchema(Tweet::class);

        $this->assertCount(30, $response->toArray());

    }

    public function testPostValidData(): void
    {
        $response = static::createClient()->request('POST', '/api/tweets',
            [
                'headers' => ["Accept: application/json"],
                'json' => [
                        'text' => 'Proves',
                        'author' => '/api/users/1'
                ]
            ]
        );

        $date = new DateTime();
        $dateStr = $date->format('c');

        $this->assertResponseStatusCodeSame(201);
        $this->assertMatchesResourceItemJsonSchema(Tweet::class);

        $this->assertJsonContains([
                'text' => 'Proves',
                'author' => '/api/users/1',
                'attachments' => [],
                'releaseDate' => $dateStr
        ]);


    }
}
