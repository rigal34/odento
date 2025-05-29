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
    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        dump($data);
        dd('FORMULAIRE VALIDE et soumis ! Données récupérées ci-dessus. On s\'arrête ici.'); 
    }else {
            // Si on entre ici, le formulaire a été soumis MAIS il N'EST PAS VALIDE
            $this->addFlash('error', 'Le formulaire contient des erreurs. Veuillez vérifier les champs.'); // Optionnel: pour l'utilisateur
            dump($form->getErrors(true, false)); // Affiche TOUTES les erreurs du formulaire (globales et par champ)
            dd('FORMULAIRE SOUMIS MAIS NON VALIDE. Voir les erreurs ci-dessus.');
        }
    }
}
