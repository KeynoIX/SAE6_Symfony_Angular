<?php

namespace App\Controller\Api;

use App\Entity\Sportif;
use App\Repository\ExerciceRepository;
use App\Repository\SeanceRepository;
use App\Repository\SportifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BilanEntrainementController extends AbstractController
{
    #[Route('/api/bilan-entrainement', name: 'api_bilan_entrainement', methods: ['GET'])]
    public function getBilanEntrainement(
        Request $request,
        SportifRepository $sportifRepository,
        SeanceRepository $seanceRepository,
        ExerciceRepository $exerciceRepository
    ): JsonResponse {
        $sportif = $this->getUser();

        if (!$sportif || !($sportif instanceof Sportif)) {
            return $this->json(['error' => 'Utilisateur non autorisé'], 403);
        }

        $dateMin = $request->query->get('date_min');
        $dateMax = $request->query->get('date_max');

        if (!$dateMin || !$dateMax) {
            return $this->json(['error' => 'Les paramètres date_min et date_max sont requis'], 400);
        }

        try {
            $dateMinObj = new \DateTime($dateMin);
            $dateMaxObj = new \DateTime($dateMax);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Format de date invalide'], 400);
        }

        $nombreSeances = count($seanceRepository->findSeancesBySportifAndPeriod($sportif->getId(), $dateMinObj, $dateMaxObj));
        $repartitionTypes = $seanceRepository->getSeanceTypeDistribution($sportif->getId(), $dateMinObj, $dateMaxObj);
        $topExercices = $exerciceRepository->findTopExercicesBySportifAndPeriod($sportif->getId(), $dateMinObj, $dateMaxObj);
        $dureeTotal = $exerciceRepository->calculateTotalDuration($sportif->getId(), $dateMinObj, $dateMaxObj);

        $bilan = [
            'sportif_id' => $sportif->getId(),
            'periode' => [
                'debut' => $dateMin,
                'fin' => $dateMax
            ],
            'nombre_total_seances' => $nombreSeances,
            'repartition_par_type' => $repartitionTypes,
            'top_exercices' => $topExercices,
            'duree_totale_minutes' => $dureeTotal,
            'duree_totale_formatee' => $this->formatDuree($dureeTotal)
        ];

        return $this->json($bilan);
    }


    private function formatDuree(int $minutes): string
    {
        $heures = floor($minutes / 60);
        $minutesRestantes = $minutes % 60;

        return sprintf('%dh%02d', $heures, $minutesRestantes);
    }
}
