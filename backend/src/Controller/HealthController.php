<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HealthController
{
    #[Route("/api/health", name: "api_health")]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            "status" => "success",
            "message" => "Backend is reachable!",
            "timestamp" => time(),
        ]);
    }
}
