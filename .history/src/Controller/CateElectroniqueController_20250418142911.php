<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CateElectroniqueRepository;

class CateElectroniqueController extends AbstractController
{
    #[Route('/categorie/electronique', name: 'app_cate_electronique')]
    public function index(CateElectroniqueRepository $repo): Response
    {
        $electronique = $repo->find(1); // Remplace 1 par l’ID réel

        return $this->render('home/categorie.html.twig', [
            'electronique' => $electronique ? $electronique->getElectronique() : 'Electronique',
        ]);
    }
}
