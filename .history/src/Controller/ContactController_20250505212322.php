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
            // $data contient maintenant : ['nom' => '...', 'email' => '...', 'sujet' => '...', 'message' => '...']

            // ---- BLOC ENVOI EMAIL (On le préparera, mais on le laisse commenté pour l'instant) ----
            /*
            try {
                // // 5. Créer l'email
                // // IMPORTANT: Tu devras configurer ces adresses quelque part
                // // (ex: dans config/services.yaml ou .env et les récupérer avec $this->getParameter(...))
                // $addressFrom = 'contact@odento-shop.com'; // Adresse d'envoi (ton site)
                // $addressTo   = 'admin@odento-shop.com';   // Adresse de réception (toi)

                // $email = (new Email())
                //     ->from($addressFrom)
                //     ->to($addressTo)
                //     ->replyTo($data['email']) // Très utile : Permet de répondre directement à l'utilisateur
                //     ->subject('Message via formulaire de contact : ' . $data['sujet'])
                //     // Corps du message en texte simple
                //     ->text('Message reçu de : ' . $data['nom'] . ' (' . $data['email'] . ")\n\n" . $data['message']);
                    // Optionnel : Corps en HTML pour une meilleure mise en forme
                    // ->html($this->renderView('emails/contact_notification.html.twig', ['contactData' => $data])); 
                
                // // 6. Envoyer l'email
                // $mailer->send($email);

                // // 7. Message de succès pour l'utilisateur
                // $this->addFlash('success', 'Votre message a bien été envoyé. Nous vous répondrons bientôt !');

            } catch (TransportExceptionInterface $e) {
                // En cas d'erreur d'envoi...
                $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer plus tard. Détail: ' . $e->getMessage());
                // Optionnel: Logguer l'erreur $e pour le débogage
            }
            */
            // ---- FIN BLOC ENVOI EMAIL ----


            // Pour l'instant, on met juste un message de succès générique
            // et on redirige pour montrer que la soumission a fonctionné
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
