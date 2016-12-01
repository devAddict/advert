<?php
namespace DA\PlatformBundle\Email;

use Da\PlatformBundle\Entity\Application;

class ApplicationMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewNotification(Application $application)
    {
        $message = new \Swift_Message(
            'Nouvelle candidature',
            'Vous avez reçu une nouvelle candidature.'
        );

        $message
            ->addTo($application->getEmail()) // Ici bien sûr il faudrait un attribut "email", j'utilise "author" à la place
            ->addFrom('decilapdenis@gmail.com')
        ;

        $this->mailer->send($message);
    }
}
