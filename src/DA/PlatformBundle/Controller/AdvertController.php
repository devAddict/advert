<?php

namespace DA\PlatformBundle\Controller;

use DA\PlatformBundle\Entity\Advert;
use DA\PlatformBundle\Form\AdvertEditType;
use DA\PlatformBundle\Form\AdvertType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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

    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('DAPlatformBundle:Advert')->find($id);

        if ($advert === null) {
            throw new NotFoundHttpException('L\'annonce avec d\'id "' . $id . '" n\existe pas.');
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($advert);
            $em->flush();

            $request->getSession()->getFlashbag()->add('notice', 'L\'annonce a bien été supprimée.');

            return $this->redirectToRoute('da_platform_index');
        }

        return $this->render('DAPlatformBundle:Advert:delete.html.twig', array(
            'advert' => $advert,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function addAction(Request $request)
    {
        $advert = new Advert();
        $advert->setDate(new \DateTime());
        $form = $this->createForm(AdvertType::class, $advert);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            $request->getSession()->getFlashbag()->add('notice', 'Annonce bien enregistrée');

            return $this->redirectToRoute('da_platform_view', array('id' => $advert->getId()));
        }
        return $this->render('DAPlatformBundle:Advert:add.html.twig', array(
            'form' => $form->createView()
        ));
    }


    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('DAPlatformBundle:Advert')->find($id);

        if ($advert === null) {
            throw new NotFoundHttpException('L\'annonce avec d\'id "' . $id . '" n\existe pas.');
        }

        $form = $this->createForm(AdvertEditType::class, $advert);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();
            $request->getSession()->getFlashbag()->add('notice', 'Annonce bien modifiée');

            return $this->redirectToRoute('da_platform_view', array('id' => $advert->getId()));
        }
        return $this->render('DAPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert,
            'form' => $form->createView()
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
            $request->getSession()->getFlashbag()->add('notice', array('info'=>'Aucune annonce à supprimée'));
        }

     return $this->redirectToRoute('da_platform_index');
    }

}
