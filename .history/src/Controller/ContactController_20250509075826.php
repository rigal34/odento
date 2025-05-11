<?php

namespace App\Controller;


use App\Form\ContactFormType; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface; 
use Symfony\Component\Mime\Email; 
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;


final class ContactController extends AbstractController
{ 

    #[Route('/contact', name: 'app_contact')]
    
    public function contact(Request $request, MailerInterface $mailer): Response
    { 

        
        $form = $this->createForm(ContactFormType::class);

        
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            
             $data = $form->getData();
             $this->addFlash('success', 'Formulaire soumis avec succès !');
             return $this->redirectToRoute('app_contact'); 
        } 

        
        return $this->render('home/contact.html.twig', [ 
            'contactForm' => $form->createView(), 
        ]);

    } 

} 
