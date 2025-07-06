<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PrivacyPolicyController extends AbstractController
{
    #[Route('/privacy/policy', name: 'app_privacy_policy')]
    public function index(): Response
    {
        return $this->render('privacy_policy/pry.html.twig', [
           
        ]);
    }
}
