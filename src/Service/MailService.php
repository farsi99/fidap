<?php

namespace App\Service;

use Twig\Environment;

class MailService
{

    private $mailer;
    private $twig;
    private $template;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }
    public function Monmail($data = array())
    {
        switch ($data['tmp']) {
            case 'create':
                $this->template = 'emails/registration.html.twig';
                break;
            case 'reset':
                $this->template = 'emails/reset.html.twig';
                break;
        }
        $message = (new \Swift_Message('Réinitialisation de mot de passe'))
            ->setFrom('farouksoule@gmail.com')
            ->setTo($data['email'])
            ->setBody(
                $this->twig->render(
                    // templates/emails/registration.html.twig
                    $this->template,
                    [
                        'nom' => $data['nomComplet'],
                        'token' => $data['token']

                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);

        return true;
    }
}
