<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CateElectroniqueController extends AbstractController
{
    #[Route('/electronique', name: 'app_cate_electronique')]
    public function electronique(EntityManagerInterface $entityManager): Response
    {
        return $this->render('home/categorie.html.twig', [
            'electronique' => $categoryElectronique ? $categoryElectronique->getName() : 'Catégorie par défaut',
          
           
        ]);
    }
}
