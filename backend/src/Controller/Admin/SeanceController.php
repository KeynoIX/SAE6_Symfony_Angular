<?php

namespace App\Controller\Admin;

use App\Entity\Participation;
use App\Entity\Seance;
use App\Entity\Sportif;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class SeanceController extends AbstractController
{
    #[Route('/seances', name: 'admin_seances')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $seances = $entityManager->getRepository(Seance::class)->findBy([
            'coach_id' => $user,
            'statut' => 'Prévue'
        ]);

        return $this->render('admin/seances.html.twig', [
            'seances' => $seances
        ]);
    }

    #[Route('/seance/valider/{id}', name: 'valider_seance')]
    public function validerSeance(EntityManagerInterface $entityManager, int $id): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->find($id);

        if (!$seance) {
            throw $this->createNotFoundException('Séance introuvable.');
        }

        $seance->setStatut('Validée');

        $entityManager->persist($seance);
        $entityManager->flush();

        return $this->redirectToRoute('admin_seances');
    }

    #[Route('/seance/presence/{id}/{sportifId}/{presence}', name: 'marquer_presence')]
    public function marquerPresence(EntityManagerInterface $entityManager, int $id, int $sportifId, string $presence): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->find($id);
        $sportif = $entityManager->getRepository(Sportif::class)->find($sportifId);

        if (!$seance || !$sportif) {
            throw $this->createNotFoundException('Séance ou sportif introuvable.');
        }

        // Vérifie si une participation existe déjà
        $participation = $entityManager->getRepository(Participation::class)->findOneBy([
            'seance' => $seance,
            'sportif' => $sportif
        ]);

        if (!$participation) {
            $participation = new Participation();
            $participation->setSeance($seance);
            $participation->setSportif($sportif);
        }

        $participation->setPresence($presence);

        $entityManager->persist($participation);
        $entityManager->flush();

        return $this->redirectToRoute('admin_seances');
    }
}
