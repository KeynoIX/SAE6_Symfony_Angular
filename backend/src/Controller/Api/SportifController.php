<?php

namespace App\Controller\Api;

use App\Entity\Sportif;
use App\Repository\SportifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/sportifs')]
class SportifController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(SportifRepository $sportifRepository): JsonResponse
    {
        return $this->json($sportifRepository->findAll(), 200, [], ['groups' => ['sportif:read', 'utilisateur:read']]);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Sportif $sportif): JsonResponse
    {
        return $this->json($sportif, 200, [], ['groups' => ['sportif:read', 'utilisateur:read']]);
    }
}
