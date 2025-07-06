<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Faq;
// use App\Entity\CateElectronique;
// use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    
    
    public function index(): Response
    {
        

         return $this->render('dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Odento');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Gestion des produits', 'fas fa-list', Article::class);
        yield MenuItem::linkToCrud('Gestion des catégories', 'fas fa-tags', Category::class);
        yield MenuItem::linkToCrud('Gestion des Posts', 'fas fa-file-alt', Post::class);

        yild MenuItem::section('Gestion du site');
        yield MenuItem::linkToCrud('Gestion des Commentaires', 'fas fa-comments', Comment::class);
        yield MenuItem::linkToCrud('Gestion des Faq', 'fas fa-question', Faq::class);
    }
}
