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
                 ->to($to) 
                 ->subject($subject)
                 ->text("$body)
                 ->html(
                     $body
                 );
         
          
             try {
                 $mailer->send($email);
                 $this->addFlash('success', 'Votre message a bien été envoyé ! Nous vous répondrons rapidement.');
             } catch (TransportExceptionInterface $e) {
                $this->addFlash('error', 'Oups ! Une erreur est survenue lors de l\'envoi du message. Erreur: ' . $e->getMessage());
                 
             }
        }






}