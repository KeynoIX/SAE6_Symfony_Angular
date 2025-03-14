<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/bilanperso', name: 'app_bilan_perso')]
final class BilanPersoController extends AbstractController
{
    private $bilanPersoService;
    

}
