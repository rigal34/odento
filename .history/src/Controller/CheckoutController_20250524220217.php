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

#[Route('/checkout', name: 'app_checkout', methods: ['GET', 'POST'])] // Accepte GET et POST
    public function checkout(Request $request): Response
    {
        // ... (récupération $cartData, vérification panier vide) ...

        $form = $this->createForm(CheckoutType::class);
        $form->handleRequest($request); // Important pour la soumission

        if ($form->isSubmitted()) { // Si le formulaire a été soumis (requête POST)
            if ($form->isValid()) {
                $data = $form->getData();
                dump($data);
                dd('FORMULAIRE VALIDE et soumis ! Données récupérées.'); 
            } else {
                // $this->addFlash('error', 'Le formulaire contient des erreurs...'); // Optionnel
                dump($form->getErrors(true, false)); 
                dd('FORMULAIRE SOUMIS MAIS NON VALIDE. Voir les erreurs ci-dessus.');
            }
        }

        // Si c'est une requête GET, ou si le formulaire soumis était invalide et qu'on n'a pas fait dd() avant,
        // on affiche le template.
        return $this->render('checkout/checkout.html.twig', [
            // ... tes variables ...
            'checkout_form' => $form->createView(),
        ]);
    }