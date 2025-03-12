<?php

namespace App\Controller\Api;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/api/articles', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): JsonResponse
    {
        $articles = $articleRepository->findAll();
        return $this->json($articles, 200, [], ['groups' => 'article:read']);
    }

    #[Route('/api/article', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, CategorieRepository $catRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $article = new Article();
        $article->setTitre($data['titre']);
        $article->setDescription($data['description']);

        if (isset($data['datePublication'])) {
            $article->setDatePublication(new \DateTime($data['datePublication']));
        } else {
            // Définir une date par défaut
            $article->setDatePublication(new \DateTime());
        }

        // Gestion de la catégorie
        if (isset($data['categorie']) && !empty($data['categorie']['id'])) {
            $categorie = $catRepo->find($data['categorie']['id']);
            if ($categorie) {
                $article->setCategorie($categorie);
            } else {
                return $this->json(['error' => 'Catégorie non trouvée'], JsonResponse::HTTP_BAD_REQUEST);
            }
        }

        $entityManager->persist($article);
        $entityManager->flush();

        // Choix du groupe de sérialisation pour éviter les boucles infinies
        return $this->json($article, JsonResponse::HTTP_CREATED, [], ['groups' => 'article:read']);
    }
}
