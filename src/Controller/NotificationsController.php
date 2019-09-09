<?php

namespace App\Controller;

use App\Entity\Users;
use Twig\Environment;

class NotificationsController {

    /*
     * @var \Swift_Mailer
     */
    private $mailer;

    /*
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer){

        $this->mailer = $mailer;
        $this->renderer = $renderer;

    }

    public function notify(Users $user){

    $message = (new \Swift_Message('Hello Email'))
        ->setFrom('gaigniere.amandine@live.fr')
        ->setTo($user->getEmail())
        ->setReplyTo($user->getEmail())
        ->setBody(
            $this->renderer->render(
                'registration.html.twig',
                ['user' => $user]
            ),
            'text/html'
        );

    $this->mailer->send($message);
    }
}