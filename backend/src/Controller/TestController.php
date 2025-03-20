<?php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test-log', name: 'test_log')]
    public function testLog(LoggerInterface $logger): Response
    {
        $logger->info('Test de log fonctionnel.');
        throw new \Exception('Erreur de test pour vérifier les logs');
    }
}
