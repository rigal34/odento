<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{
    #[Route('/auth', name: 'app_auth')]
    public function a(): Response
    {
        return $this->render('home/register.html.twig', [
           
        ]);
    }
}
