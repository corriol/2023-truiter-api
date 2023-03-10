<?php

namespace App\Tests\Service;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use App\Entity\Tweet;
use App\Entity\User;
use DateTime;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class TweetsTest extends ApiTestCase
{

    // propietat que emmagatzemar√† el token en iniciar el test.
    private string $token;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = $this->createToken("user", "user");
        $this->client = static::createClient();
        $encoder = $this->client->getContainer()->get(JWTEncoderInterface::class);
        $this->client = static::createClient([], ["auth_bearer" => $encoder->encode(["username" => "user", "role" => ["ROLE_USER"]])]);
        //$this->client = $this->createAuthenticatedClient("user", "user");
    }

    protected function createAuthenticatedClient($username = 'user', $password = 'password'): Client
    {
        $client = static::createClient();
        $response = $client->request('POST', '/login', [
            'headers' => ['Content-Type: application/json'],
            'json' => [
                'username' => $username,
                'password' => $password,
            ],
        ]);

        $data = $response->toArray();
        //sprintf('Bearer %s', $data['token']));

        return static::createClient([], ['auth_bearer' => $data['token']]);
    }


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

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password *
     *
     */
    protected function createToken($username = 'user', $password = 'password'): string
    {
        $client = static::createClient();
        $response = $client->request('POST', '/login', [
            'headers' => ['Content-Type: application/json'],
            'json' => [
                'username' => $username,
                'password' => $password,
            ],
        ]);

        $data = $response->toArray();
        //sprintf('Bearer %s', $data['token']));
        return $data['token'];
    }


    public function testGetCollectionReturnsValidData(): void
    {
        $response = $this->client->request('GET', '/api/tweets',
            [
                "headers" => ["Accept: application/json"],
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceCollectionJsonSchema(Tweet::class);

        $this->assertCount(20, $response->toArray());

    }

    public function testPostValidData(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["username" => "user"]);

        $response = $this->client->request('POST', '/api/tweets',
            [
                'headers' => ["Accept: application/json"],
                'json' => [
                    'text' => 'Proves',
                    'author' => '/api/users/' . $user->getId(),
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
            'author' => ["username" => "user"],
            'likeCount' => 0
        ]);
    }

    public function testPostNoData(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["username" => "user"]);

        $response = $this->client->request('POST', '/api/tweets',
            [
                'headers' => ["Accept: application/json"],
                'json' => [
                    //'text' => '',
                    // 'author' => '',
                ]
            ]
        );

        //dump($response);
        $date = new DateTime();
        $dateStr = $date->format('c');

        $this->assertResponseStatusCodeSame(422);
        //$this->assertMatchesResourceItemJsonSchema(Tweet::class);

        $this->assertJsonContains([
            'violations' => [
                [
                    "propertyPath" => "text",
                    "message" => "This value should not be blank."
                ],
                [
                    "propertyPath" => "author",
                    "message" => "This value should not be blank."
                ]

            ]
        ]);
    }
    public function testPostInvalidData(): void
    {

        //$this->expectException(UnexpectedValueException::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["username" => "user"]);

        $response = $this->client->request('POST', '/api/tweets',
            [
                'headers' => ["Accept: application/json"],
                'json' => [
                    'text' => '1',
                    'author' => '/api/users/25',
                ]
            ]
        );

        //dump($response);
        $date = new DateTime();
        $dateStr = $date->format('c');

        $this->assertResponseStatusCodeSame(400);
        //$this->assertMatchesResourceItemJsonSchema(Tweet::class);

/*        $this->assertJsonContains([
            'violations' => [
                [
                    "propertyPath" => "text",
                    "message" => "This value is too short. It should have 2 characters or more."
                ],
                [
                    "propertyPath" => "author",
                    "message" => "This value should not be blank."
                ]

            ]
        ]);*/
    }

}
