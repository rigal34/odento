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
        // Tu récupères la première catégorie Electronique
        $cat = $repo->findOneBy([]);

        // Tu envoies son nom directement comme string (comme pour vetement)
        return $this->render('home/categorie.html.twig', [
            'electronique' => $cat ? $cat->getElectronique() : 'Electronique',
        ]);
    }
}
