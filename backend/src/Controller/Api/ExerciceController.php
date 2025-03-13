<?php

namespace App\Controller\Api;

use App\Entity\Exercice;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/exercices')]
class ExerciceController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ExerciceRepository $exerciceRepository): JsonResponse
    {
        return $this->json($exerciceRepository->findAll(), 200, [], ['groups' => 'exercice:read']);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Exercice $exercice): JsonResponse
    {
        return $this->json($exercice, 200, [], ['groups' => 'exercice:read']);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $exercice = new Exercice();
        $exercice->setNom($data['nom'])
            ->setDescription($data['description'] ?? '')
            ->setDureeEstimee($data['duree_estimee'] ?? 0)
            ->setDifficulte($data['difficulte'] ?? 'Facile');

        $em->persist($exercice);
        $em->flush();

        return $this->json($exercice, 201, [], ['groups' => 'exercice:read']);
    }
}
