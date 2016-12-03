<?php

namespace DA\PlatformBundle\Controller;

use DA\PlatformBundle\Entity\Advert;
use DA\PlatformBundle\Entity\AdvertSkill;
use DA\PlatformBundle\Entity\Application;
use DA\PlatformBundle\Entity\Image;
use DA\PlatformBundle\Entity\Skill;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction($page, Request $request)
    {
       if ($page < 1) {
            throw new NotFoundHttpException('La page "' . $page . '" n\'existe pas.');
        }

        $nbPerPage = 3;
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('Knp_paginator');
        $listAdverts = $em->getRepository('DAPlatformBundle:Advert')->_getAvertAll($paginator, $page, $nbPerPage);
        
        return $this->render('DAPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts,
        ));
    }

    public function viewAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('DAPlatformBundle:Advert')->_getAvert($id);
        
        return $this->render('DAPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('DAPlatformBundle:Advert')->find($id);

        if ($advert === null) {
            throw new NotFoundHttpException('L\'annonce avec d\'id "' . $id . '" n\existe pas.');
        }

        foreach ($advert as $category) {
            $advert->removeCategory($category);
        }

        $em->persist($advert);
        $em->flush();
    }

    public function addAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashbag()->add('notice', 'Annonce bien enregistrée');

            return $this->redirectToRoute('da_platform_view', array('id' => 5));
        }
        return $this->render('DAPlatformBundle:Advert:add.html.twig');
    }


    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('DAPlatformBundle:Advert')->find($id);

        if ($advert === null) {
            throw new NotFoundHttpException('L\'annonce avec d\'id "' . $id . '" n\existe pas.');
        }
        
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashbag()->add('notice', 'Annonce bien modifiée');

            return $this->redirectToRoute('da_platform_view', array('id' => 5));
        }

        return $this->render('DAPlatformBundle:Advert:edit.html.twig', array(
            'advert'=>$advert
        ));
    }

    public function menuAction($limit)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('DAPlatformBundle:Advert');

        $listAdverts = $repository->findBy(
            array(),
            array('date' => 'desc'),
            $limit,
            0
        );

        return $this->render('DAPlatformBundle:Advert:menu.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }

    public function purgeAction($days, Request $request)
    {
        $purge = $this->get('da_platform.purger.advert')->purge($days);

        if ($purge > 0) {
            $request->getSession()->getFlashbag()->add('notice', array('info'=>'Nombre d\'annonce supprimée: ' . $purge));
        }else{
            $request->getSession()->getFlashbag()->add('notice', array('info'=>'Aucune annonce a supprimée'));
        }

     return $this->redirectToRoute('da_platform_home');
    }

}
