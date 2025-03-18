<?php

namespace App\Controller\Admin;

use App\Entity\Coach;
use App\Entity\Seance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class PaiementController extends AbstractController
{
    #[Route('/paiements', name: 'admin_paiements')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $coachs = $entityManager->getRepository(Coach::class)->findAll();
        $paiements = [];

        foreach ($coachs as $coach) {
            $seancesValidees = $entityManager->getRepository(Seance::class)->count([
                'coach_id' => $coach,
                'statut' => 'Validée'
            ]);

            $total = $seancesValidees * $coach->getTarifHoraire();

            $paiements[] = [
                'coach' => $coach->getPrenom() . ' ' . $coach->getNom(),
                'seances_validees' => $seancesValidees,
                'tarif_horaire' => $coach->getTarifHoraire(),
                'total_du' => $total,
            ];
        }

        return $this->render('admin/paiements.html.twig', [
            'paiements' => $paiements
        ]);
    }
}
