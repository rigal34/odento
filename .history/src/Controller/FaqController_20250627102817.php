<?php

namespace App\Controller;

use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FaqController extends AbstractController
{
    #[Route('/faq', name: 'app_faq')]
    public function faq(FaqRepository $faq_repository): Response
    {
        return $this->render('faq/faq.html.twig', [
            
        ]);
    }
}
