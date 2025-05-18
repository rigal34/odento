<?php

namespace App\Controller;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function panier(Request $request, ArticleRepository $articleRepository): Response
{
    $session = $request->getSession();//getSession est la methode de l objet request qui va chercher tout les objets  session qui se rapportent a la session
    $cart = $session->get('cart', []); // recuperer le panier de la session, ou un tableau vide s'il n'existe pas

    //dump($cart); // dump est une fonction de symfony qui permet d'afficher les variables dans la console de debug
    // dump($cart); // Affiche le contenu du panier dans la console de debug
  $detailedCart = [];
  $totalCartAmont = 0;

  foreach ($cartRaw as $id => $quantity) {
    $article = $articleRepository-> find($id); // Récupérer l'article correspondant à l'ID
    if ($article) {
        $lineTotal = $article-> getPrice() µ
    }
  }


 return $this->render('cart/panier.html.twig', [
    'controller_name' => 'Affichage du panier',
    'cart_items_raw' => $cart,
 ]);

}      
  
//Methode pour ajouter un produit au panier que je pourrait utiliser pour utiliser sur une bouton
#[Route('/panier/ajouter/{id}', name: 'app_add_cart')]

public function addToCart(Request $request, int $id): RedirectResponse
{
    $session = $request->getSession();
    $cart = $session->get('cart', []);

    // Vérifier si le produit est déjà dans le panier
    if (!empty($cart[$id])) {
        $cart[$id]++;
    } else {
        $cart[$id] = 1;
    }

    $session->set('cart', $cart);
   
    $this->addFlash('success', 'Produit ajouté avec succès au panier');

    // redirection vers la page precedente si possible
    $referer = $request->headers->get('referer');
    if ($referer) {
        return new RedirectResponse($referer);
    }
    return $this->redirectToRoute('app_home');
}

}

