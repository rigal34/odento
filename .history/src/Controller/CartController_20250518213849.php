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
  $totalCartAmount = 0;

  foreach ($cart as $id => $quantity) {
    $article = $articleRepository-> find($id); // Récupérer l'article correspondant à l'ID
    if ($article) {
        $lineTotal = $article-> getPrice() * $quantity;
        $detailedCart[] = [
            'product' => $article,
            'quantity' => $quantity,
            'lineTotal' => $lineTotal,
        ];
        $totalCartAmount += $lineTotal; // Calculer le montant total du panier       
    }
  }


 return $this->render('home/panier.html.twig', [
    'controller_name' => 'Affichage du panier',
    'detailed_cart_items' => $detailedCart, 
   'total_cart_amount' => $totalCartAmount,
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










 #[Route('/panier/supprimer/{id}', name: 'app_remove_cart')]
 public function removeFromCart(int $id, Request $request): RedirectResponse 
 {  
    $session = $request->getSession();
    $cart = $session->get('cart', []);

    if (isset($cart[$id])) {//Verifie si dans le tableau cart il y a un id correspondant a celui de l article avant de le supprimer
        unset($cart[$id]); // Supprimer l'article du panier
        $this-> addFlash('success', 'Produit supprimé avec succès du panier');
    } else {
        $this-> addFlash('warning', 'Le produit que vous essayez de retirer n\'était pas dans le panier.');
    }
    $session->set('cart', $cart); // Mettre à jour le panier dans la session
    return $this->redirectToRoute('app_panier'); // Rediriger vers la page du panier
 }

    // Methode icon caddie .

   public function cartIcon(Request $request): Response
   {
    $session = $request->getSession();
    $cart = $se



   }


 #[Route('/panier/vider', name: 'app_cart_clear')]
 public function clearCart(Request $request): RedirectResponse
 { 
    $session = $request->getSession();
    $session->remove('cart'); // Supprimer le panier de la session
    $this-> addFlash('success', 'Le panier a été vidé avec succès.');
    return $this->redirectToRoute('app_panier'); // Rediriger vers la page du panier
  }
}



