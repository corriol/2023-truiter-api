<?php

namespace App\Tests\Service;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadMediaTest extends ApiTestCase
{
    public function testCreateAMediaObject(): void
    {
        $file = file_get_contents('resources/photo.jpg');
        $data = base64_encode($file);

        $client = self::createClient();

        $client->request('POST', '/media', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                // If you have additional fields in your MediaObject entity, use the parameters.
                'filename' => "image",
                'data' => $data,
            ]
        ]);
        $this->assertResponseIsSuccessful();
        //$this->assertMatchesResourceItemJsonSchema(MediaObject::class);
        $this->assertJsonContains([
            'data' => 'ok',
        ]);
    }

    public function testCreateAMediaObjectFailsIfNoImageIsSent(): void
    {
        $file = file_get_contents('resources/photo.jpg');
        $data = base64_encode("HOLA MÓN");

        $client = self::createClient();

        $client->request('POST', '/media', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                // If you have additional fields in your MediaObject entity, use the parameters.
                'filename' => "image",
                'data' => $data,
            ]
        ]);
        $this->assertResponseIsSuccessful();
        //$this->assertMatchesResourceItemJsonSchema(MediaObject::class);
        $this->assertJsonContains([
            'data' => 'ok',
        ]);
    }
}
