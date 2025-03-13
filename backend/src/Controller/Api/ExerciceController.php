<?php

namespace App\Controller\Api;

use App\Entity\Exercice;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExerciceController extends AbstractController
{
    #[Route('/api/exercices', methods: ['GET'])]
    public function index(ExerciceRepository $exerciceRepository): JsonResponse
    {
        return $this->json($exerciceRepository->findAll(), 200, [], ['groups' => 'exercice:read']);
    }

    #[Route('/api/exercice', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['nom'], $data['duree_estimee'], $data['difficulte'])) {
            return new JsonResponse(["error" => "Champs obligatoires manquants"], 400);
        }

        $exercice = new Exercice();
        $exercice->setNom($data['nom']);
        $exercice->setDescription($data['description'] ?? '');
        $exercice->setDureeEstimee($data['duree_estimee']);
        $exercice->setDifficulte($data['difficulte']);

        $em->persist($exercice);
        $em->flush();

        return $this->json($exercice, JsonResponse::HTTP_CREATED, [], ['groups' => 'exercice:read']);
    }

    #[Route('/api/exercice/{id}', methods: ['PUT'])]
    public function update(Request $request, Exercice $exercice, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['nom'])) $exercice->setNom($data['nom']);
        if (isset($data['description'])) $exercice->setDescription($data['description']);
        if (isset($data['duree_estimee'])) $exercice->setDureeEstimee($data['duree_estimee']);
        if (isset($data['difficulte'])) $exercice->setDifficulte($data['difficulte']);

        $em->flush();

        return $this->json($exercice, 200, [], ['groups' => 'exercice:read']);
    }

    #[Route('/api/exercice/{id}', methods: ['DELETE'])]
    public function delete(Exercice $exercice, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($exercice);
        $em->flush();

        return new JsonResponse(['message' => 'Exercice supprimé'], 204);
    }
}
