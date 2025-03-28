<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailProduitController extends AbstractController
{
    #[Route('/detail_produit', name: 'app_detail_produit')]
    public function etail_produit(): Response
    {
        return $this->render('home/detail_produit.html.twig', [

        ]);
    }
}
