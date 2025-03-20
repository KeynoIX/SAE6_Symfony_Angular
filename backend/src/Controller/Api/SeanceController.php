<?php

namespace App\Controller\Api;

use App\Entity\Coach;
use App\Entity\Seance;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SeanceController extends AbstractController
{
    #[Route('/api/seances', methods: ['GET'])]
    public function index(SeanceRepository $seanceRepository): JsonResponse
    {
        $seances = $seanceRepository->findAll();

        return $this->json($seances, 200, [], ['groups' => 'seance:read']);
    }

    #[Route('/api/seance', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['date_heure'], $data['type_seance'], $data['theme_seance'], $data['niveau_seance'], $data['statut'])) {
            return new JsonResponse(["error" => "Champs obligatoires manquants"], 400);
        }

        $seance = new Seance();
        $seance->setDateHeure(new \DateTime($data['date_heure']));
        $seance->setTypeSeance($data['type_seance']);
        $seance->setThemeSeance($data['theme_seance']);
        $seance->setNiveauSeance($data['niveau_seance']);
        $seance->setStatut($data['statut']);

        if (isset($data['coach_id'])) {
            $coach = $em->getRepository(Coach::class)->find($data['coach_id']);
            if (!$coach) {
                return new JsonResponse(["error" => "Coach non trouvé"], 404);
            }
            $seance->setCoachId($coach);
        }

        $em->persist($seance);
        $em->flush();

        return $this->json($seance, 201, [], ['groups' => 'seance:read']);
    }

    #[Route('/api/seance/{id}', methods: ['PUT'])]
    public function update(Request $request, Seance $seance, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['date_heure'])) $seance->setDateHeure(new \DateTime($data['date_heure']));
        if (isset($data['type_seance'])) $seance->setTypeSeance($data['type_seance']);
        if (isset($data['theme_seance'])) $seance->setThemeSeance($data['theme_seance']);
        if (isset($data['niveau_seance'])) $seance->setNiveauSeance($data['niveau_seance']);
        if (isset($data['statut'])) $seance->setStatut($data['statut']);

        if (isset($data['coach_id'])) {
            $coach = $em->getRepository(Coach::class)->find($data['coach_id']);
            if (!$coach) {
                return new JsonResponse(["error" => "Coach non trouvé"], 404);
            }
            $seance->setCoachId($coach);
        }

        $em->flush();

        return $this->json($seance, 200, [], ['groups' => 'seance:read']);
    }

    #[Route('/api/seance/{id}', methods: ['GET'])]
    public function show(Seance $seance): JsonResponse
    {
        return $this->json($seance, 200, [], ['groups' => 'seance:read']);
    }

    #[Route('/api/seance/{id}', methods: ['DELETE'])]
    public function delete(Seance $seance, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($seance);
        $em->flush();

        return new JsonResponse(['message' => 'Séance supprimée'], 204);
    }

    #[Route('/api/seance/{id}/inscription', methods: ['POST'])]
    public function inscrireSportif(int $id, EntityManagerInterface $em): JsonResponse
    {
        $seance = $em->getRepository(Seance::class)->find($id);
        $sportif = $this->getUser();

        if (!$seance) {
            return $this->json(['message' => 'Séance non trouvée'], 404);
        }

        if ($seance->getSportifs()->count() >= 3) {
            return $this->json(['message' => 'Cette séance est complète.'], 400);
        }

        if ($seance->getSportifs()->contains($sportif)) {
            return $this->json(['message' => 'Vous êtes déjà inscrit à cette séance.'], 400);
        }

        try {
            $seance->addSportif($sportif);
            $em->persist($seance);
            $em->flush();

            return $this->json(['message' => 'Inscription réussie'], 200);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/api/sportif/{id}/seances/historique', methods: ['GET'])]
    public function getHistoriqueSeances(string $id, EntityManagerInterface $em): JsonResponse
    {
        $now = new \DateTime();
        
        $seances = $em->getRepository(Seance::class)->createQueryBuilder('s')
            ->join('s.sportifs', 'sp')
            ->where('sp.id = :sportifId')
            ->andWhere('s.statut = :statut')
            ->andWhere('s.date_heure < :now')
            ->setParameter('sportifId', $id)
            ->setParameter('statut', 'Validée')
            ->setParameter('now', $now)
            ->orderBy('s.date_heure', 'DESC')
            ->getQuery()
            ->getResult();
        
        return $this->json($seances, 200, [], ['groups' => 'seance:read']);
    }
}
