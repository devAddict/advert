<?php

namespace DA\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DAPlatformBundle:Default:index.html.twig');
    }
}
