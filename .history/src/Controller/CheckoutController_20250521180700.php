<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CheckoutController extends AbstractController
{
    private CartService $cartService;
    public function __construct(CartService $cartService)
    {
         $cartData = $this->cartService->getDetailedCart(); 
   
   
        }
    #[Route('/checkout', name: 'app_checkout')]
    public function checkout(): Response
    {
        return $this->render('checkout/checkout.html.twig', [
            'controller_name' => 'CheckoutController',
        ]);
    }
}
