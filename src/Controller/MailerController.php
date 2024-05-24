<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerController extends AbstractController
{
    #[Route('/email', priority:5)]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('admin@example.com')
            ->to('user@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See <strong>Twig</strong> integration for better HTML integration!</p>');

        $mailer->send($email);

        return new Response('Mail is sent!');
    }
}