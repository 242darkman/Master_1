<?php

namespace Miniframework\App\Mail;

use Swift_Mailer;

class Mailer
{
    private $mailer;

    /**
     * constructeur
     */
    public function __construct()
    {
        $transport = (new Swift_SmtpTransport("smtp.mailtrap.io", 2525))
            ->setUsername("1a2b3c4d5e6f7g")
            ->setPassword("1a2b3c4d5e6f7g");
        $mailer = new Swift_Mailer($transport);
    }

    public function index($name, \Swift_Mailer $mailer)
    {
        $message = (new Swift_Message())
            ->setSubject("Here should be a subject")
            ->setFrom(["support@example.com"])
            ->setTo(["newuser@example.com" => "New Mailtrap user"])
            ->setCc([
                "product@example.com" => "Product manager",
            ]);
        $message->setBody();
        $message->addPart(
            "Votre achat",
            "text / plain"
        );
        $message->attach(
            Swift_Attachment::fromPath(" / path / to / confirmation . pdf")
        );
        $mailer->send($message);
    }
}
