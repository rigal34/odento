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


            // 8. Redirection vers la même page (pattern Post-Redirect-Get)
            // Important pour éviter que l'utilisateur renvoie le formulaire s'il rafraîchit la page après succès
            return $this->redirectToRoute('app_contact'); 
        }

        // 9. Si la requête est en GET (premier affichage) OU si le formulaire soumis n'est PAS valide :
        // On affiche le template Twig en lui passant la "vue" du formulaire
        return $this->render('contact/index.html.twig', [ // Assure-toi que le chemin est bon
            'contactForm' => $form->createView(), // createView() est nécessaire pour Twig
        ]);
    }
}











        return $this->render('home/contact.html.twig', [
          
        ]);
    }
}
