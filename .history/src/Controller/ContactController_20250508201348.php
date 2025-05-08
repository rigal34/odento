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

       // ---- REMPLACE L'ANCIENNE SECTION PAR CELLE-CI ----
       if ($form->isSubmitted()) { // On vérifie d'abord juste si le formulaire est soumis
        dd([ // dd va afficher un tableau avec toutes ces informations
            'is_submitted' => $form->isSubmitted(), // Devrait être true
            'is_valid' => $form->isValid(),         // Devrait être false si les champs sont vides
            'errors_form_global' => $form->getErrors(), // Erreurs globales du formulaire
            'errors_all_fields' => $form->getErrors(true, false), // Erreurs de tous les champs (sans les erreurs globales)
            'data_form' => $form->getData(),        // Quelles données le formulaire a-t-il après soumission ?
            'nom_field_data' => $form->get('nom')->getData(), // Donnée spécifique du champ 'nom'
            'nom_field_errors' => $form->get('nom')->getErrors(true) // Erreurs spécifiques du champ 'nom'
        ]);
    }
    // ---- FIN DE LA NOUVELLE SECTION DE DÉBOGAGE ------


        if ($form->isSubmitted() && $form->isValid()) {
            
             $data = $form->getData();
             $this->addFlash('success', 'Formulaire soumis avec succès ! (Logique d\'envoi d\'email à implémenter)');
             return $this->redirectToRoute('app_contact'); 
        } 

        
        return $this->render('home/contact.html.twig', [ 
            'contactForm' => $form->createView(), 
        ]);

    } 

} 
