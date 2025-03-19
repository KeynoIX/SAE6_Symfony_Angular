<?php

namespace App\Controller\Admin;

use App\Entity\Coach;
use App\Entity\Seance;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
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
                'id' => $coach->getId(), // Ajout de l'ID
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

    #[Route('/paiements/pdf/{id}/{periode}', name: 'paiement_pdf')]
    public function generatePdf(EntityManagerInterface $entityManager, int $id, string $periode): Response
    {
        $coach = $entityManager->getRepository(Coach::class)->find($id);

        if (!$coach) {
            throw $this->createNotFoundException('Coach introuvable.');
        }

        $seances = $entityManager->getRepository(Seance::class)->findBy([
            'coach_id' => $coach,
            'statut' => 'Validée'
        ]);

        $totalHeures = count($seances);
        $montantTotal = $totalHeures * $coach->getTarifHoraire();

        $html = $this->renderView('admin/paiement_pdf.html.twig', [
            'coach' => $coach,
            'periode' => $periode,
            'totalHeures' => $totalHeures,
            'tarifHoraire' => $coach->getTarifHoraire(),
            'montantTotal' => $montantTotal
        ]);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Fiche de paie de ' . $coach->getNom() . ' ' . $coach->getPrenom() . ' ' . $periode . '.pdf"'
            ]
        );
    }
}
