<?php
// src/DA/PlatformBundle/DataFixtures/ORM/LoadCategory.php

namespace DA\PlatformBundle\DataFixtures\ORM;

use DA\PlatformBundle\Entity\AdvertSkill;
use DA\PlatformBundle\Entity\Application;
use DA\PlatformBundle\Entity\Image;
use DA\PlatformBundle\Entity\Skill;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DA\PlatformBundle\Entity\Advert;

class LoadAdvert implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        for ($i = 0;$i < 2;$i++) {
            $advert = new Advert();
            $advert->setTitle('Recherche développeur php');
            $advert->setAuthor('Décilap');
            $advert->setContent('Nous recherchons un développeur symfony débutant');
            $advert->setEmail('decilapdenis@gmail.com');

            $image = new Image();
            $image->setUrl("http://www.adiph.org/images/deposer_votre_annonce.png");
            $image->setAlt("votre annonce");

            $advert->setImage($image);
            $manager->persist($advert);
            
            for ($j = 0;$j < 4;$j++) {
                $application = new Application();
                $application->setAuthor('Denis');
                $application->setContent('Je suis motivé et dynamique');
                $application->setEmail('decilapdenis@gmail.com');

                $application->setAdvert($advert);
                $manager->persist($application);
            }
        }
        
        $manager->flush();
    }
    
    
    
}