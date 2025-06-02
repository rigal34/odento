<?php
namespace App\Service;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{


	public function __construct(private MailerInterface $mailer)
    {}


    public function sendEmail(string $to, string $subject, string $body): void
    {
        $email = (new \Symfony\Component\Mime\Email())
            ->from('






}