<?php
namespace DA\PlatformBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use DA\PlatformBundle\Email\ApplicationMailer;
use DA\PlatformBundle\Entity\Application;
use Swift_RfcComplianceException;
use Symfony\Component\Config\Definition\Exception\Exception;

class ApplicationCreationListener
{
    /**
     * @var ApplicationMailer
     */
    private $applicationMailer;

    public function __construct(ApplicationMailer $applicationMailer)
    {
        $this->applicationMailer = $applicationMailer;

    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // On ne veut envoyer un email que pour les entités Application
        if (!$entity instanceof Application) {
            return;
        }
        try {

            $this->applicationMailer->sendNewNotification($entity);
        } catch (Swift_RfcComplianceException $e) {
            throw new Swift_RfcComplianceException('mail');
        }
    }
}
