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

#[Route('/checkout', name: 'app_checkout', methods: ['GET', 'POST'])]
public function checkout(Request $request): Response
{
   
 

    if ($request->isMethod('POST')) {
       
    }

    $cartData = $this->cartService->getDetailedCart();

    if (empty($cartData['items'])) {
        $this->addFlash('warning', 'Votre panier est vide. Vous ne pouvez pas passer à la caisse pour le moment.');
        return $this->redirectToRoute('app_panier');
    }

    $form = $this->createForm(CheckoutType::class);
    
 
    $form->handleRequest($request);
 
   

    if ($form->isSubmitted()) {
        
        if ($form->isValid()) {
            $data = $form->getData();
           
            
           
            $this->addFlash('info', 'Test: Formulaire VALIDE et soumis !');
           
            $session = $request->getSession();
            $session->set('checkout_form_data_test', $data);
            return $this->redirectToRoute('app_checkout_confirm'); 

        } else {
           
            $this->addFlash('error', 'Test: Formulaire soumis mais NON VALIDE.');
            
        }
    } else {
        
            
            $this->addFlash('error', 'Test: Problème de soumission du formulaire (POST mais non soumis).');
        
    }

    return $this->render('checkout/checkout.html.twig', [
        'controller_name' => 'Processus de Commande',
        'checkout_items' => $cartData['items'],
        'checkout_total_amount' => $cartData['total'],
        'checkout_form' => $form->createView(),
    ]);
}


#[Route('/checkout/confirmation', name: 'app_checkout_confirm', methods: ['GET'])]

public function confirmation(Request $request): Response

$cartData = $this->cartService->getDetailedCart();

$session = $request->getSession();
    $checkoutData = $session->get('checkout_data');
    if (empty($car))
}