<?php

namespace App\Tests\Service;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Tweet;
use App\Entity\User;
use DateTime;

class TweetsTest extends ApiTestCase
{
    public function testLogin(): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        $user = new User();
        $user->setUsername('user2');
        $user->setName('test user');
        $user->setCreatedAt(new DateTime());
        $user->setVerified(false);
        $user->setPassword(
            $container->get('security.user_password_hasher')->hashPassword($user, '1234')
        );

        $manager = $container->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        // retrieve a token
        $response = $client->request('POST', '/login', [
            'headers' => ['Content-Type: application/json'],
            'json' => [
                'username' => 'user',
                'password' => 'user',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        // test not authorized
        $client->request('GET', '/api/tweets', [
            'headers' => ['Content-Type: application/json', 'Accept: application/json']
        ]);
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/api/tweets',
            [
                'auth_bearer' => $json['token'],
                'headers' => ['Accept: application/json']
            ]);
        $this->assertResponseIsSuccessful();
    }

    public function testGetCollectionReturnsValidData(): void
    {
        //TODO: Adding authentication
        $response = static::createClient()->request('GET', '/api/tweets',
            [ "headers" => ["Accept: application/json"]]
        );

        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceCollectionJsonSchema(Tweet::class);

        $this->assertCount(20, $response->toArray());

    }
    public function testPostValidData(): void
    {
        // TODO: Adding authentication
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
                'createdAt' => $dateStr,
                'author' => '/api/users/1',
                'likeCount' => 0
        ]);


    }
}
