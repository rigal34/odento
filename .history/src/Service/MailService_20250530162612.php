<?php
namespace App\Service;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{


	public function __construct(private MailerInterface)
    {
       
    }



   }







}