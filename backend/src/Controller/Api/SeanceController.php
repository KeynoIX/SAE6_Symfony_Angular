<?php

namespace App\Controller\Api;

use App\Entity\Seance;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/seances')]
class SeanceController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(SeanceRepository $seanceRepository): JsonResponse
    {
        return $this->json($seanceRepository->findAll(), 200, [], ['groups' => 'seance:read']);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Seance $seance): JsonResponse
    {
        return $this->json($seance, 200, [], ['groups' => 'seance:read']);
    }
}
