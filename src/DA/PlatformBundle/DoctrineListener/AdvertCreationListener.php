<?php
namespace DA\PlatformBundle\DoctrineListener;

use DA\PlatformBundle\Email\AdvertDeleteMailer;
use DA\PlatformBundle\Entity\Advert;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Swift_RfcComplianceException;

class AdvertCreationListener
{
    /**
     * @var AdvertDeleteMailer
     */
    private $application_delete_Mailer;

    public function __construct(AdvertDeleteMailer $application_delete_Mailer)
    {
        $this->application_delete_Mailer = $application_delete_Mailer;

    }

    public function postRemove(LifecycleEventArgs $args)
    {

        $entity = $args->getObject();

        // On ne veut envoyer un email que pour les entités Application
        if (!$entity instanceof Advert) {
            return;
        }
        try {
            $this->application_delete_Mailer->sendDeleteNotification($entity);
        } catch (Swift_RfcComplianceException $e) {
            throw new Swift_RfcComplianceException('mail');
        }
    }
}
