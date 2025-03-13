<?php

namespace App\Controller\Api;

use App\Entity\Sportif;
use App\Repository\SportifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SportifController extends AbstractController
{
    #[Route('/api/sportifs', methods: ['GET'])]
    public function index(SportifRepository $sportifRepository): JsonResponse
    {
        return $this->json($sportifRepository->findAll(), 200, [], ['groups' => ['sportif:read', 'utilisateur:read']]);
    }
    #[Route('/api/sportif', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['password'], $data['prenom'], $data['nom'], $data['niveau_sportif'])) {
            return new JsonResponse(["error" => "Champs obligatoires manquants"], 400);
        }

        $sportif = new Sportif();
        $sportif->setEmail($data['email']);
        $sportif->setPrenom($data['prenom']);
        $sportif->setNom($data['nom']);
        $sportif->setRoles(['ROLE_SPORTIF']);
        $sportif->setNiveauSportif($data['niveau_sportif']);
        $sportif->setDateInscription(new \DateTime());

        $hashedPassword = $passwordHasher->hashPassword($sportif, $data['password']);
        $sportif->setPassword($hashedPassword);

        $em->persist($sportif);
        $em->flush();

        return $this->json($sportif, 201, [], ['groups' => ['sportif:read', 'utilisateur:read']]);
    }

    #[Route('/api/sportif/{id}', methods: ['PUT'])]
    public function update(Request $request, Sportif $sportif, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['prenom'])) $sportif->setPrenom($data['prenom']);
        if (isset($data['nom'])) $sportif->setNom($data['nom']);
        if (isset($data['niveau_sportif'])) $sportif->setNiveauSportif($data['niveau_sportif']);

        $em->flush();

        return $this->json($sportif, 200, [], ['groups' => ['sportif:read', 'utilisateur:read']]);
    }

    #[Route('/api/sportif/{id}', methods: ['DELETE'])]
    public function delete(Sportif $sportif, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($sportif);
        $em->flush();

        return new JsonResponse(['message' => 'Sportif supprimé'], 204);
    }
}
