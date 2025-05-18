<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_panier_show')]
    public function panier(Request $request): Response

    $_SESSION
    {
        return $this->render('cart/panier.twig', [
            'controller_name' => 'CartController',
        ]);
    }
}
