<?php
// src/DA/PlatformBundle/DataFixtures/ORM/LoadCategory.php

namespace DA\PlatformBundle\DataFixtures\ORM;

use DA\PlatformBundle\Entity\AdvertSkill;
use DA\PlatformBundle\Entity\Application;
use DA\PlatformBundle\Entity\Image;
use DA\PlatformBundle\Entity\Skill;
use DA\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DA\PlatformBundle\Entity\Advert;

class LoadUser implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        $listNames = array(
            'ALexandre',
            'Marine',
            'Denis'
        );

        foreach ($listNames as $name) {
            $user = new User();

            $user->setUsername($name);
            $user->setPassword($name);

            $user->setSalt('');
            $user->setRoles(array('ROLE_USER'));
            $manager->persist($user);
        }
        $manager->flush();
    }
    
    
    
}