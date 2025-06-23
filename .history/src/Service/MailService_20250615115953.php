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
                 ->cc('') //copie carbone
                 ->bcc('') //copie carbone invisible    
                    ->replyTo('') //adresse de réponse
                    ->priority(Email::PRIORITY_NORMAL) //priorité normale

                    
                 ->subject($subject)
                 ->text($body)
                 ->html(
                     $body
                 );
                 $this->mailer->send($email);
                }

          
 }

