<?php

namespace App\Controller\Api;

use App\Entity\Coach;
use App\Repository\CoachRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/coachs')]
class CoachController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(CoachRepository $coachRepository): JsonResponse
    {
        return $this->json($coachRepository->findAll(), 200, [], ['groups' => ['coach:read', 'utilisateur:read']]);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Coach $coach): JsonResponse
    {
        return $this->json($coach, 200, [], ['groups' => ['coach:read', 'utilisateur:read']]);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $coach = new Coach();
        $coach->setEmail($data['email'])
            ->setPrenom($data['prenom'])
            ->setNom($data['nom'])
            ->setPassword($data['password'])
            ->setRoles(['ROLE_COACH'])
            ->setSpecialites($data['specialites'] ?? [])
            ->setTarifHoraire($data['tarif_horaire'] ?? 0);

        $em->persist($coach);
        $em->flush();

        return $this->json($coach, 201, [], ['groups' => ['coach:read', 'utilisateur:read']]);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Coach $coach, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['specialites'])) $coach->setSpecialites($data['specialites']);
        if (isset($data['tarif_horaire'])) $coach->setTarifHoraire($data['tarif_horaire']);

        $em->flush();

        return $this->json($coach, 200, [], ['groups' => ['coach:read', 'utilisateur:read']]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Coach $coach, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($coach);
        $em->flush();

        return new JsonResponse(['message' => 'Coach supprimé'], 204);
    }
}
