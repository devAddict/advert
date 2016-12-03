<?php
namespace DA\PlatformBundle\Email;

use DA\PlatformBundle\Entity\Advert;

class AdvertDeleteMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendDeleteNotification(Advert $advert)
    {
        $message = new \Swift_Message(
            'Suppression d\'annonce',
            'Votre annonce n\'a aucune candidature à ce jour, donc nous avons décidé de la supprimé'
        );

        $message
            ->addTo($advert->getEmail()) 
            ->addFrom('decilapdenis@gmail.com')
        ;

        $this->mailer->send($message);
    }
}
