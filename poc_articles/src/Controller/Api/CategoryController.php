<?php

namespace App\Controller\Api;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/api/categories', name: 'app_api_categories')]
    public function index(CategorieRepository $repo): JsonResponse
    {
        $categories = $repo->findAll();
        return $this->json($categories, JsonResponse::HTTP_OK, [], ['groups' => 'categorie:read']);
        /*return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/CategoryController.php',
        ]);*/
    }
}
