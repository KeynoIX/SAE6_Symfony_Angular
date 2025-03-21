<?php

namespace App\Controller\Admin;

use App\Entity\Participation;
use App\Entity\Seance;
use App\Entity\Sportif;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class SeanceController extends AbstractController
{
    #[Route('/seances', name: 'admin_seances')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $queryBuilder = $entityManager->getRepository(Seance::class)->createQueryBuilder('s');

        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            $queryBuilder->where('s.coach_id = :coach')
                ->setParameter('coach', $user);
        }

        $seancesPrevues = clone $queryBuilder;
        $seancesValidees = clone $queryBuilder;
        $seancesAnnulees = clone $queryBuilder;

        $seancesPrevues = $seancesPrevues->andWhere('s.statut = :statut')
            ->setParameter('statut', 'Prévue')
            ->getQuery()
            ->getResult();

        $seancesValidees = $seancesValidees->andWhere('s.statut = :statut')
            ->setParameter('statut', 'Validée')
            ->getQuery()
            ->getResult();

        $seancesAnnulees = $seancesAnnulees->andWhere('s.statut = :statut')
            ->setParameter('statut', 'Annulée')
            ->getQuery()
            ->getResult();

        return $this->render('admin/seances.html.twig', [
            'seancesPrevues' => $seancesPrevues,
            'seancesValidees' => $seancesValidees,
            'seancesAnnulees' => $seancesAnnulees
        ]);
    }

    #[Route('/seance/valider/{id}', name: 'valider_seance')]
    public function validerSeance(EntityManagerInterface $entityManager, int $id): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->find($id);

        if (!$seance) {
            throw $this->createNotFoundException('Séance introuvable.');
        }

        $sportifs = $seance->getSportifs();
        foreach ($sportifs as $sportif) {
            $participation = $entityManager->getRepository(Participation::class)->findOneBy([
                'seance' => $seance,
                'sportif' => $sportif
            ]);

            if (!$participation || !in_array($participation->getPresence(), ['présent', 'absent'])) {
                $this->addFlash('danger', 'Tous les sportifs doivent être notés avant de valider la séance.');
                return $this->redirectToRoute('admin', ['routeName' => 'admin_seances']);
            }
        }

        $seance->setStatut('Validée');
        $entityManager->persist($seance);
        $entityManager->flush();

        $this->addFlash('success', 'La séance a été validée avec succès.');
        return $this->redirectToRoute('admin', ['routeName' => 'admin_seances']);
    }

    #[Route('/seance/annuler/{id}', name: 'annuler_seance')]
    public function annulerSeance(EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $this->getUser();

        if (in_array('ROLE_COACH', $user->getRoles())) {
            $this->addFlash('danger', 'Pour annuler la séance, veuillez contacter un responsable.');
            return $this->redirectToRoute('admin', ['routeName' => 'admin_seances']);
        }

        $seance = $entityManager->getRepository(Seance::class)->find($id);

        if (!$seance) {
            throw $this->createNotFoundException('Séance introuvable.');
        }

        $seance->setStatut('Annulée');
        $entityManager->persist($seance);
        $entityManager->flush();

        $this->addFlash('success', 'La séance a été annulée avec succès.');
        return $this->redirectToRoute('admin', ['routeName' => 'admin_seances']);
    }

    #[Route('/seance/presence/{id}/{sportifId}/{presence}', name: 'marquer_presence')]
    public function marquerPresence(EntityManagerInterface $entityManager, int $id, int $sportifId, string $presence): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->find($id);
        $sportif = $entityManager->getRepository(Sportif::class)->find($sportifId);

        if (!$seance || !$sportif) {
            throw $this->createNotFoundException('Séance ou sportif introuvable.');
        }

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

        $this->addFlash('success', 'La présence du sportif a été mise à jour.');
        return $this->redirectToRoute('admin', ['routeName' => 'admin_seances']);
    }
}
