<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiEndPointController extends AbstractController
{
    #[Route('/api/end/point', name: 'app_api_end_point')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new test!',
            'path' => 'src/Controller/ApiEndPointController.php',
        ]);
    }
}
