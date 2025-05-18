<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_panier_show')]
    public function panier(Request $request): Response

    $session = $request->getSession();//getSession est la methode de l objet request qui va chercher tout les objets  session qui se rapportent a la session
    $cart = $session->get('cart', []); 
 return $this->render('cart/panier.html.twig', [
   
 ])

       
     
   
}
