<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CompteController extends AbstractController
{
    #[Route('/compte', name: 'app_compte')]
    public function compt(): Response
    {
        return $this->render('home/compte.html.twig', [
            
        ]);
    }
}
