<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController {

    #[Route('/email',name:'email')]
    public function sendEmail(MailerInterface $mailer): Response {
        $email = (new Email())
            ->from('yastatlar@alu.edu.gva.es')
            ->to('correu@valid.com')
            ->subject('Prova Symfony Mailer!')
            ->text('Prova d’enviament de correu amb Symfony')
            ->html('<p>Es pot integrar twig.</p>');
        try{
            $mailer->send($email);
            return new Response('Email Enviat Correctament');
        } catch (\Exception $e) {
            return new Response("Error Enviant Email");
        }
    }
}