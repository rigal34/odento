<?php
namespace App\Service;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{


	public function __construct(private MailerInterface $mailer)
    {}


    public function sendEmail(string $to, string $subject, string $body): void
    {
         $email = (new Email())
                 ->from('rigalbruno2@gmail.com') 
                 ->to('rigalbruno2@gmail.com') 
                 ->subject('Nouveau message de contact Odento-SHOP: ' . $sujet)
                 ->text("Vous avez reçu un nouveau message de contact :\n\nDe: $nom <$emailExpediteur>\nSujet: $sujet\n\nMessage:\n$messageUtilisateur")
                 ->html(
                     "<p>Vous avez reçu un nouveau message de contact :</p>" .
                     "<p><strong>De :</strong> " . htmlspecialchars($nom) . " (" . htmlspecialchars($emailExpediteur) . ")</p>" .
                     "<p><strong>Sujet :</strong> " . htmlspecialchars($sujet) . "</p>" .
                     "<p><strong>Message :</strong></p>" .
                     "<p>" . nl2br(htmlspecialchars($messageUtilisateur)) . "</p>"
                 );
         
          
             try {
                 $mailer->send($email);
                 $this->addFlash('success', 'Votre message a bien été envoyé ! Nous vous répondrons rapidement.');
             } catch (TransportExceptionInterface $e) {
                $this->addFlash('error', 'Oups ! Une erreur est survenue lors de l\'envoi du message. Erreur: ' . $e->getMessage());
                 
             }
        }






}