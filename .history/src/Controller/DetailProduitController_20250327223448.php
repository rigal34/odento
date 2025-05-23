<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailProduitController extends AbstractController
{
    #[Route('/detail/produit', name: 'app_detail_produit')]
    public function index(): Response
    {
        return $this->render('detail_produit/index.html.twig', [
            'controller_name' => 'DetailProduitController',
        ]);
    }
}
