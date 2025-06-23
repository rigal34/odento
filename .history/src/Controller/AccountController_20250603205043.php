<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
final class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function compte(): Response
    {
        //recuperer l'utilisateur connecté si il y en a un
        $user = $this->getUser();

        $orders = $user->getOrders();
        return $this->render('account/CompteUtilisateur.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
}
