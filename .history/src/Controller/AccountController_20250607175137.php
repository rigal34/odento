<?php

namespace App\Controller;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("IS_AUTHENTICATED_FULLY")]

final class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
   
    public function compte(): Response
    {
        //recuperer l'utilisateur connecté si il y en a un
        $user = $this->getUser();
        /** @var \App\Entity\User $user */
         
        if (!$user) {
           
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login'); 
        }

        $orders = $user->getOrders(); 

        return $this->render('account/CompteUtilisateur.html.twig', [
            'controller_name' => 'Mon Espace Compte',
            'user' => $user,
            'orders' => $orders,
        ]);
    }
    #[Route('/account/orders', name: 'app_account_orders')]
    
}
