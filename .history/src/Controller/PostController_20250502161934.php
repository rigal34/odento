<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    #[Route('/actualites', name: 'app_actu')]
    public function index(PostRepository $): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
}
