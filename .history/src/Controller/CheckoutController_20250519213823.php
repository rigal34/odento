<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_checkout')]
    public function checkout(): Response
    {
        return $this->render('checkout/checkout.html', [
            'controller_name' => 'CheckoutController',
        ]);
    }
}
