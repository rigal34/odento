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
             $nom = $data['nom'];
             $emailExpediteur = $data['email'];
             $sujet = $data['sujet'];
             $messageUtilisateur = $data['message'];
         
             // Construis l'email
             $email = (new Email())
                 ->from('contact@odento-shop.com') // Adresse d'expéditeur pour ton site
                 ->to('rigalrigal') // <<< REMPLACE ÇA par ton email où tu veux recevoir
                 ->replyTo($emailExpediteur) // Pour répondre directement à l'utilisateur
                 ->subject('Nouveau message de contact Odento-SHOP: ' . $sujet)
                 ->text("Vous avez reçu un nouveau message de contact :\n\nDe: $nom <$emailExpediteur>\nSujet: $sujet\n\nMessage:\n$messageUtilisateur")
                 ->html(
                     "<p>Vous avez reçu un nouveau message de contact :</p>" .
                     "<p><strong>De :</strong> " . htmlspecialchars($nom) . " (" . htmlspecialchars($emailExpediteur) . ")</p>" .
                     "<p><strong>Sujet :</strong> " . htmlspecialchars($sujet) . "</p>" .
                     "<p><strong>Message :</strong></p>" .
                     "<p>" . nl2br(htmlspecialchars($messageUtilisateur)) . "</p>"
                 );
         
             // Envoie l'email
             try {
                 $mailer->send($email);
                 $this->addFlash('success', 'Votre message a bien été envoyé ! Nous vous répondrons rapidement.');
             } catch (TransportExceptionInterface $e) {
                 $this->addFlash('error', 'Oups ! Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer plus tard.');
                 // Pour le développement, tu pourrais vouloir voir plus de détails :
                 // $this->addFlash('error', 'Erreur envoi email : ' . $e->getMessage() . ' Debug: ' . $e->getDebug());
             }
             return $this->redirectToRoute('app_contact'); 
        } 

        
        return $this->render('home/contact.html.twig', [ 
            'contactForm' => $form->createView(), 
        ]);

    } 

} 
