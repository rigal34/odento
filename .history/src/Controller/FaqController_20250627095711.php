<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FaqController extends AbstractController
{
    #[Route('/faq', name: 'app_faq')]
    public function faq(): Response
    {
        return $this->render('HOME/faq.html.twig', [
            
        ]);
    }
}
