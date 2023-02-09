<?php

namespace App\Tests\Service;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Tweet;

class TweetTest extends ApiTestCase
{
    public function testGetCollectionReturnsValidData(): void
    {
        $response = static::createClient()->request('GET', '/api/tweets',
            [ "headers" => ["Accept: application/json"]]
        );

        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceCollectionJsonSchema(Tweet::class);

    }
}
