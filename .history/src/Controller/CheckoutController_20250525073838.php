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
    dump('--- Début méthode checkout() ---');
    dump('Méthode HTTP reçue: ' . $request->getMethod());

    if ($request->isMethod('POST')) {
        dump('La requête est bien de type POST.');
        dump('Données POST brutes reçues:', $request->request->all());
    }

    $cartData = $this->cartService->getDetailedCart();

    if (empty($cartData['items'])) {
        $this->addFlash('warning', 'Votre panier est vide. Vous ne pouvez pas passer à la caisse pour le moment.');
        return $this->redirectToRoute('app_panier');
    }

    $form = $this->createForm(CheckoutType::class);
    
    dump('Avant $form->handleRequest()');
    $form->handleRequest($request); // Traitement de la requête par le formulaire
    dump('Après $form->handleRequest()');

    if ($form->isSubmitted()) {
        dump('Formulaire DÉTECTÉ COMME SOUMIS par Symfony ($form->isSubmitted() est VRAI)');
        if ($form->isValid()) {
            $data = $form->getData();
            dump($data);
            dd('FORMULAIRE VALIDE et soumis ! Données récupérées.'); 
        } else {
            dump($form->getErrors(true, false)); 
            dd('FORMULAIRE SOUMIS MAIS NON VALIDE. Voir les erreurs ci-dessus.');
        }
    } else {
        // Si on arrive ici APRÈS avoir tenté de soumettre le formulaire (donc en POST)
        if ($request->isMethod('POST')) {
            dd('ERREUR : La requête était POST, mais $form->isSubmitted() est FAUX. Vérifiez la structure HTML du formulaire (noms des champs, form_start/end) et les données POST ci-dessus.');
        }
        dump('Le formulaire n\'est pas considéré comme soumis (requête GET initiale ou POST non reconnu par le formulaire). Affichage du formulaire.');
    }

    return $this->render('checkout/checkout.html.twig', [
        'controller_name' => 'Processus de Commande',
        'checkout_items' => $cartData['items'],
        'checkout_total_amount' => $cartData['total'],
        'checkout_form' => $form->createView(),
    ]);
}
}