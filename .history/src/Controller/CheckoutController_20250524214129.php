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

    // J'ajuste la structure de l'if pour être plus clair pour le GET vs POST invalide
    if ($form->isSubmitted()) { // On traite la soumission
        if ($form->isValid()) {
            // Formulaire soumis ET VALIDE
            $data = $form->getData();
            dump($data);
            dd('FORMULAIRE VALIDE et soumis ! Données récupérées ci-dessus. On s\'arrête ici.'); 
            // Plus tard, on remplacera dd() par la redirection vers l'étape suivante
            // return $this->redirectToRoute('app_checkout_confirmation_ou_paiement');
        } else {
            // Formulaire soumis MAIS PAS VALIDE
            // On peut ajouter un message flash ici aussi si on veut, mais le render ci-dessous
            // réaffichera le formulaire avec les erreurs grâce à Bootstrap.
            // $this->addFlash('error', 'Le formulaire contient des erreurs.');
            // Pour déboguer, on peut dumper les erreurs :
            // dump($form->getErrors(true, false));
            // Pas de dd() ici pour que la page se réaffiche avec les erreurs du formulaire.
        }
    }
    // Si ce n'est pas une soumission (GET) ou si c'était une soumission invalide (et qu'on n'a pas fait dd())
    // on affiche la page avec le formulaire.
    return $this->render('checkout/checkout.html.twig', [
        'controller_name' => 'Processus de Commande',
        'checkout_items' => $cartData['items'],
        'checkout_total_amount' => $cartData['total'],
        'checkout_form' => $form->createView(),
    ]);
} // <<< C'EST ICI LA BONNE ACCOLADE FERMANTE pour la méthode checkout()

} // <<< Et celle-ci pour la classe CheckoutController