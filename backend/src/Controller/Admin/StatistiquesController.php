<?php

namespace App\Controller\Admin;

use App\Entity\Seance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class StatistiquesController extends AbstractController
{
    #[Route('/statistiques', name: 'admin_statistiques')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $totalSeances = $entityManager->getRepository(Seance::class)->count([]);

        $seances = $entityManager->getRepository(Seance::class)->findAll();
        $remplissageTotal = 0;
        $nombreSeancesAvecSportifs = 0;

        $themeCounts = [];

        foreach ($seances as $seance) {
            $nombreSportifs = count($seance->getSportifs());

            if ($nombreSportifs > 0) {
                $remplissageTotal += $nombreSportifs;
                $nombreSeancesAvecSportifs++;
            }

            $theme = $seance->getThemeSeance();
            if (!isset($themeCounts[$theme])) {
                $themeCounts[$theme] = 0;
            }
            $themeCounts[$theme]++;
        }

        $tauxRemplissage = $nombreSeancesAvecSportifs > 0 ? round($remplissageTotal / $nombreSeancesAvecSportifs, 2) : 0;

        return $this->render('admin/stats.html.twig', [
            'totalSeances' => $totalSeances,
            'tauxRemplissage' => $tauxRemplissage,
            'themeCounts' => $themeCounts
        ]);
    }
}
