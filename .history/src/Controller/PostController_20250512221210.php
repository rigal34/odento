<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Entity\Comment;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManagerInterface;
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
    public function detail(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request); // On demande au formulaire de gérer la requête

        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire a été soumis et est valide
            $user = $this->getUser(); // Récupère l'utilisateur connecté

            if ($user) { // Vérifie si un utilisateur est bien connecté
                // L'utilisateur est connecté, on peut enregistrer le commentaire
                $comment->setAuthor($user);
                $comment->setPost($post);
                $comment->setCreatedAt(new \DateTimeImmutable());
                $comment->setIsApproved(true); // Approuvé par défaut pour l'instant

                $entityManager->persist($comment);
                $entityManager->flush();

                // Message de succès adapté si on changeait isApproved plus tard
                if ($comment->isApproved()) {
                    $this->addFlash('success', 'Votre commentaire a été ajouté avec succès !');
                } else {
                    $this->addFlash('info', 'Votre commentaire a été soumis et est en attente d\'approbation.');
                }
                
                
                return $this->redirectToRoute('app_actu_detail', ['id' => $post->getId()]);

            } else {
               
                $this->addFlash('warning', 'Vous devez être connecté pour laisser un commentaire.');
                
                return $this->redirectToRoute('app_actu_detail', ['id' => $post->getId()]);
                
            }
        }

       
        return $this->render('post/detail.html.twig', [
            'post' => $post,
            'comment_form' => $form->createView(), 
        ]);
    }
}

