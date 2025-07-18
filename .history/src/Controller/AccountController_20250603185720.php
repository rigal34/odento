<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function compte(): Response
    {
        return $this->render('account/Compte.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
}
