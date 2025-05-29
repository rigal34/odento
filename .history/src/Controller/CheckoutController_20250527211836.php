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
 
    dump('Formulaire est-il soumis ? : ' . ($form->isSubmitted() ? 'OUI' : 'NON'));

    if ($form->isSubmitted()) {
        dump('Dans if ($form->isSubmitted())');
        if ($form->isValid()) {
            $data = $form->getData();
            dump('FORMULAIRE EST VALIDE. Données:', $data);
            
            // Au lieu de dd(), on met un flash et on redirige pour voir si on atteint ce point
            $this->addFlash('info', 'Test: Formulaire VALIDE et soumis !');
            // Stockons les données en session pour voir
            $session = $request->getSession();
            $session->set('checkout_form_data_test', $data);
            return $this->redirectToRoute('app_checkout'); // Redirige vers la même page pour voir le flash

        } else {
            dump('FORMULAIRE EST SOUMIS MAIS NON VALIDE. Erreurs:', $form->getErrors(true, false));
            $this->addFlash('error', 'Test: Formulaire soumis mais NON VALIDE.');
            // On laisse rendre la page pour voir les erreurs sur le formulaire si le thème les affiche
        }
    } else {
        if ($request->isMethod('POST')) {
            // Ce cas est important : c'est un POST, mais Symfony ne considère pas le formulaire comme soumis.
            dump('ERREUR DEBUG : La requête était POST, mais $form->isSubmitted() est FAUX.');
            $this->addFlash('error', 'Test: Problème de soumission du formulaire (POST mais non soumis).');
        }
    }

    return $this->render('checkout/checkout.html.twig', [
        'controller_name' => 'Processus de Commande',
        'checkout_items' => $cartData['items'],
        'checkout_total_amount' => $cartData['total'],
        'checkout_form' => $form->createView(),
    ]);
}
}