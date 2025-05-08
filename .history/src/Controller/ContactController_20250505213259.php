<?php

namespace App\Controller;

// --- TOUS TES 'USE' STATEMENTS ICI ---
use App\Form\ContactFormType; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface; 
use Symfony\Component\Mime\Email; 
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
// --- FIN DES 'USE' ---

final class ContactController extends AbstractController
{ // Accolade ouvrante de la CLASSE

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    { // Accolade ouvrante de la METHODE contact

        // 1. Créer le formulaire
        $form = $this->createForm(ContactFormType::class);

        // 2. Gérer la requête
        $form->handleRequest($request);

        // 3. Vérifier soumission et validité
        if ($form->isSubmitted() && $form->isValid()) {
            // ... (Bloc IF - logique de traitement, redirection, etc.) ...
             $data = $form->getData();
             $this->addFlash('success', 'Formulaire soumis avec succès ! (Logique d\'envoi d\'email à implémenter)');
             return $this->redirectToRoute('app_contact'); 
        } // Accolade fermante du IF

        // 9. Rendre la vue (si GET ou formulaire invalide)
        return $this->render('contact/index.html.twig', [ // ou 'home/contact.html.twig' si c'est le bon chemin
            'contactForm' => $form->createView(), 
        ]);

    } // Accolade fermante de la METHODE contact() - DOIT ÊTRE LÀ

} // Accolade fermante de la CLASSE ContactController - DOIT ÊTRE LA DERNIÈRE CHOSE

// ===> TOUT CE QUI SUIT DOIT ÊTRE SUPPRIMÉ ! <===
// Il semble que tu aies ces lignes en trop à la fin de ton fichier :
//         ]);
//     }
// }