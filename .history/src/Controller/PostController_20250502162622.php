<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    #[Route('/actualites', name: 'app_actu')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/actualites/{id}', name: 'app_actu_detail')]
    public function detail(Post $post): Response
    {
        return $this->render('post/index.html.twig', [
            'post' => $post
        
    }
}
