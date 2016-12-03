<?php
// src/DA/PlatformBundle/DataFixtures/ORM/LoadCategory.php

namespace DA\PlatformBundle\DataFixtures\ORM;

use DA\PlatformBundle\Entity\Skill;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSkill implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $names = array('PHP', 'Symfony', 'C++', 'Java', 'Photoshop', 'Blender', 'Bloc-note');

        foreach ($names as $name) {
            $skill = new Skill();
            // On la persiste
            $skill->setName($name);
            $manager->persist($skill);
        }

        $manager->flush();
    }
}