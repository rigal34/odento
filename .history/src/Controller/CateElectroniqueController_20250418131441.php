<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CateElectroniqueController extends AbstractController
{
    #[Route('/electronique', name: 'app_cate_electronique')]
    public function index(): Response
    {
        return $this->render('home/categorie.html.twig', [
            'vetement' => $categoryVetement ? $categoryVetement->getName() : 'Catégorie par défaut',
          
           
        ]);
    }
}
