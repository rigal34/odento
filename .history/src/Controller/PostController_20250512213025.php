<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{

    // :::::::::::::CREATION POUR LE BLOG ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    #[Route('/actualites', name: 'app_actu')]
    public function actu(PostRepository $postRepository): Response
    {
        return $this->render('post/postindex.html.twig', [
            'posts' => $postRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

// ::::::::::CREATION POUR LE DETAIL DU BLOG ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

    #[Route('/actualites/{id}', name: 'app_actu_detail')]
    public function detail(Post $post): Response
    {
        return $this->render('post/detail.html.twig', [
            'post' => $post
        ]);
    }
}
