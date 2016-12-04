<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 29/11/2016
 * Time: 21:55
 */

namespace DA\PlatformBundle\Purger;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\Date;

class AdvertPuger
{
    private $em;
    private $repository;

    /**
     * DAAntispam constructor.
     */
    public function __construct(EntityManager $em)
    {
       $this->em = $em;
       $this->repository = $em->getRepository('DAPlatformBundle:Advert');
    }

    public function purge($days)
    {
        $count = 0;
        $date = new \Datetime("-$days DAYS");
        $advertEmpty = $this->repository->_getAdvertBeforeDate($date);

        foreach ($advertEmpty as $advert) {
            $applications = $advert->getApplications();
            
            /**
             * arrayCollection isEmpty() relation
             */
            if ($applications->isEmpty()) {
                $this->em->remove($advert);
                $this->em->flush();
                $count++;
            }
        }
        return $count;
    }

    	
}