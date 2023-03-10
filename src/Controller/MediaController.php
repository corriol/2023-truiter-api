<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File as FileObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MediaController extends AbstractController
{
    #[Route('/media', name: 'app_media', methods: ["POST"])]
    public function index(Request $request, ValidatorInterface $validator, SerializerInterface $serializer): Response
    {
        $content = $request->getContent();
        $data = json_decode($content, true);
        $tmpPath = sys_get_temp_dir().'/sf_upload'.uniqid();
        file_put_contents($tmpPath, base64_decode($data["data"]));
        $uploadedFile = new FileObject($tmpPath);

        $violations = $validator->validate(
            $uploadedFile,
            [
                new NotBlank([
                    'message' => 'Please select a file to upload'
                ]),
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'image/*'
                    ]
                ])
            ]);

        if (count($violations) > 0) {
            unlink($tmpPath);
            return new JsonResponse($serializer->serialize('Invalid data', 'jsonld'),
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $extension = $uploadedFile->guessExtension();
        $uploadedFile->move();

        return $this->json(["data"=>$tmpPath]);
    }
}
