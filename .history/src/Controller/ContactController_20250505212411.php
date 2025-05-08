<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController


{

    #[Route('/contact', name: 'app_contact')]
   
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        
        $form = $this->createForm(ContactFormType::class);

        
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            

          
            $data = $form->getData();
           
            $this->addFlash('success', 'Formulaire soumis avec succès ! (Logique d\'envoi d\'email à implémenter)');


           
            return $this->redirectToRoute('app_contact'); 
        }

        return $this->render('contact/index.html.twig', [ 
            'contactForm' => $form->createView(), 
        ]);
    }
}











        return $this->render('home/contact.html.twig', [
          
        ]);
    }
}
