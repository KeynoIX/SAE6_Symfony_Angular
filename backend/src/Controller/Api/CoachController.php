<?php

namespace App\Controller\Api;

use App\Entity\Coach;
use App\Repository\CoachRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CoachController extends AbstractController
{
    #[Route('/api/coachs', methods: ['GET'])]
    public function index(CoachRepository $coachRepository): JsonResponse
    {
        return $this->json($coachRepository->findAll(), 200, [], ['groups' => ['coach:read', 'utilisateur:read']]);
    }

    #[Route('/api/coach/{id}', methods: ['GET'])]
public function show(Coach $coach): JsonResponse
{
    return $this->json($coach, 200, [], ['groups' => ['coach:read', 'utilisateur:read']]);
}


    #[Route('/api/coach', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $coach = new Coach();
        $coach->setEmail($data['email']);
        $coach->setPrenom($data['prenom']);
        $coach->setNom($data['nom']);
        $coach->setRoles(['ROLE_COACH']);
        $coach->setSpecialites($data['specialites'] ?? []);
        $coach->setTarifHoraire($data['tarif_horaire'] ?? 0);

        $hashedPassword = $passwordHasher->hashPassword($coach, $data['password']);
        $coach->setPassword($hashedPassword);

        $em->persist($coach);
        $em->flush();

        return $this->json($coach, 201, [], ['groups' => ['coach:read', 'utilisateur:read']]);
    }

    #[Route('/api/coach/{id}', methods: ['PUT'])]
    public function update(Request $request, Coach $coach, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['specialites'])) $coach->setSpecialites($data['specialites']);
        if (isset($data['tarif_horaire'])) $coach->setTarifHoraire($data['tarif_horaire']);

        $em->flush();

        return $this->json($coach, 200, [], ['groups' => ['coach:read', 'utilisateur:read']]);
    }

    #[Route('/api/coach/{id}', methods: ['DELETE'])]
    public function delete(Coach $coach, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($coach);
        $em->flush();

        return new JsonResponse(['message' => 'Coach supprimé'], 204);
    }
}
