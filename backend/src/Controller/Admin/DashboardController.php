<?php

namespace App\Controller\Admin;

use App\Entity\Coach;
use App\Entity\Exercice;
use App\Entity\Seance;
use App\Entity\Sportif;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{

    private Security $security;

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Backend');
    }

    public function configureMenuItems(): iterable
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_COACH')) {
            yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

            yield MenuItem::section('Gestion des séances');
            yield MenuItem::linkToCrud('Créer/modifier séances', 'fa fa-folder', Seance::class);
            yield MenuItem::linkToRoute('Séances à valider', 'fa fa-check', 'admin_seances');
        }
        
        if ($this->security->isGranted('ROLE_ADMIN')) {
            yield MenuItem::section('Administration');
            yield MenuItem::linkToCrud('Gestion des coachs', 'fa fa-user', Coach::class);
            yield MenuItem::linkToCrud('Gestion des sportifs', 'fa fa-user', Sportif::class);
            yield MenuItem::linkToRoute('Statistiques', 'fa fa-chart-bar', 'admin_statistiques');
            yield MenuItem::linkToCrud('Annuler séance planifiée', 'fa fa-folder', Seance::class);
            yield MenuItem::linkToRoute('Fiche de paiement', 'fa fa-money-bill', 'admin_paiements');
        }
    }
}
