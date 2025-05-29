<?php

namespace App\Controller;

use App\Service\CartService;
use App\Form\CheckoutType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CheckoutController extends AbstractController
{
    private CartService $cartService;
public function __construct(CartService $cartService)
{
    $this->cartService = $cartService;
}

#[Route('/checkout', name: 'app_checkout')]
public function checkout(Request $request): Response
{
    $cartData = $this->cartService->getDetailedCart();

    if (empty($cartData['items'])) {
        $this->addFlash('warning', 'Votre panier est vide. Vous ne pouvez pas passer à la caisse pour le moment.');
        return $this->redirectToRoute('app_panier');
    }

    $form = $this->createForm(CheckoutType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid->()) {
        $data = $form->getData();
       
    
       
    }
    

    return $this->render('checkout/checkout.html.twig', [
        'controller_name' => 'Processus de Commande',
        'checkout_items' => $cartData['items'],
        'checkout_total_amount' => $cartData['total'],
        'checkout_form' => $form->createView(),
    ]);
}
}
