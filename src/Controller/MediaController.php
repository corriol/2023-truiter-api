<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    #[Route('/media', name: 'app_media')]
    public function index(Request $request): Response
    {
        $content = $request->getContent();

        $data = json_decode($content, true);

        $tmpPath = sys_get_temp_dir().'/sf_upload'.uniqid();

        file_put_contents($tmpPath, base64_decode($data["data"]));

        return $this->json(["data"=>$tmpPath]);
    }
}
