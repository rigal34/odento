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
   if (empty($cartData['items'])) {
            $this->addFlash('warning', 'Votre panier est vide. Vous ne pouvez pas passer à la caisse pour le moment.');
            return $this->redirectToRoute('app_panier'); // Redirige vers la page panier (assure-toi que 'app_panier' est le bon nom)
        }
   
        }
    #[Route('/checkout', name: 'app_checkout')]
    public function checkout(): Response
    {
        return $this->render('checkout/checkout.html.twig', [
           'controller_name' => 'Processus de Commande',
           'checkout_items' => $cartData['items'],
           'checkout_total_amount' => $cartData['total'],
        ]);
    }
}
