<?php

namespace DA\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('La page "' . $page . '" inexistante.');
        }


        $listAdverts = array(
            array(
                'title'   => 'Recherche développpeur Symfony',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Mission de webmaster',
                'id'      => 2,
                'author'  => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Offre de stage webdesigner',
                'id'      => 3,
                'author'  => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date'    => new \Datetime())
    );


        return $this->render('DAPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }

    public function viewAction($id, Request $request)
    {
        $advert = array(
            'title'   => 'Recherche développpeur Symfony2',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
        );


        return $this->render('DAPlatformBundle:Advert:view.html.twig', array(
            'advert'=>$advert,
        ));
    }

    public function addAction(Request $request)
    {
        /*if ($request->isMethod('POST')) {
            $request->getSession()->getFlashbag()->add('notice', 'Annonce bien enregistrée');

            return $this->redirectToRoute('da_platform_view', array('id' => 5));
        }
        return $this->render('DAPlatformBundle:Advert:add.html.twig');*/
        $antispam = $this->container->get('da_platform.antispam');
        $text = '....';
        if ($antispam->isSpam($text)) {
            throw new Exception('Votre message est détecté comme un spam !');
        }
    }

    public function deleteAction()
    {
        return $this->render('DAPlatformBundle:Advert:delete.html.twig');
    }

    public function editAction($id, Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashbag()->add('notice', 'Annonce bien modifiée');

            return $this->redirectToRoute('da_platform_view', array('id' => 5));
        }

        $advert = array(
            'title'   => 'Recherche développpeur Symfony',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
        );

        return $this->render('DAPlatformBundle:Advert:edit.html.twig', array(
            'advert'=>$advert,
        ));
    }

    public function menuAction($limit)
    {
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );

        return $this->render('DAPlatformBundle:Advert:menu.html.twig', array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listAdverts' => $listAdverts
        ));
    }

}
