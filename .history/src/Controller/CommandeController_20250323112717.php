<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function commande(): Response
    {
        return $this->render('home/commande.html.twig', [
            
        ]);
    }
}
