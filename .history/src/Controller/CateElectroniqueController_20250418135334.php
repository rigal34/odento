<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CateElectronique;

final class CateElectroniqueController extends AbstractController
{
    #[Route('/electronique', name: 'app_cate_electronique')]
public function electronique(EntityManagerInterface $entityManager): Response
{
    $categoryElectronique = $entityManager->getRepository(CateElectronique::class)->findOneBy([]);

    return $this->render('home/categorie.html.twig', [
        'electronique' => $categoryElectronique ? $categoryElectronique->getElectronique() : 'Catégorie par défaut',
    ]);
}

}
