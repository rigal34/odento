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
// Dans src/Controller/CheckoutController.php

#[Route('/checkout', name: 'app_checkout', methods: ['GET', 'POST'])]
public function checkout(Request $request): Response
{
    // ---- DÉBUT DÉBOGAGE ----
    dump('Méthode checkout() appelée. Méthode HTTP : ' . $request->getMethod());

    if ($request->isMethod('POST')) {
        dump('La requête est bien de type POST.');
        dump('Données POST reçues :', $request->request->all());
    }
    // ---- FIN DÉBOGAGE ----

    $cartData = $this->cartService->getDetailedCart();

    if (empty($cartData['items'])) {
        $this->addFlash('warning', 'Votre panier est vide. Vous ne pouvez pas passer à la caisse pour le moment.');
        return $this->redirectToRoute('app_panier');
    }

    $form = $this->createForm(CheckoutType::class);
    $form->handleRequest($request); // ESSENTIEL : le formulaire traite la requête

    if ($form->isSubmitted()) {
        dump('Formulaire DÉTECTÉ COMME SOUMIS par Symfony !'); // Nouveau dump
        if ($form->isValid()) {
            $data = $form->getData();
            dump($data);
            dd('FORMULAIRE VALIDE et soumis ! Données récupérées.'); 
        } else {
            dump($form->getErrors(true, false)); 
            dd('FORMULAIRE SOUMIS MAIS NON VALIDE. Voir les erreurs ci-dessus.');
        }
    } else {
        // Si c'est une requête POST mais que le formulaire n'est pas "soumis" pour Symfony
        if ($request->isMethod('POST')) {
            dd('La requête était POST, mais $form->isSubmitted() est FAUX. Problème avec handleRequest ou la structure du formulaire HTML (noms des champs).');
        }
    }

    return $this->render('checkout/checkout.html.twig', [
        'controller_name' => 'Processus de Commande',
        'checkout_items' => $cartData['items'],
        'checkout_total_amount' => $cartData['total'],
        'checkout_form' => $form->createView(),
    ]);
}