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
    public function indexAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('La page "' . $page . '" inexistante.');
        }

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('DAPlatformBundle:Advert');
        $listAdverts = $repository->_findAll();

        return $this->render('DAPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }

    public function viewAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('DAPlatformBundle:Advert')->find($id);

        if ($advert === null) {
            throw new NotFoundHttpException('L\'annonce avec d\'id "' . $id . '" n\existe pas.');
        }

        $listApplications = $em
            ->getRepository('DAPlatformBundle:Application')
            ->findBy(array('advert' => $advert));

        $listAdvertSkills = $em
            ->getRepository('DAPlatformBundle:AdvertSkill')
            ->findBy(array('advert' => $advert));

        return $this->render('DAPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills,
        ));
    }

    public function addAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $advert = new Advert();
        $advert->setTitle('Recherche développeur php');
        $advert->setAuthor('Décilap');
        $advert->setContent('Nous recherchons un développeur symfony débutant');

        $image = new Image();
        $image->setUrl("http://www.adiph.org/images/deposer_votre_annonce.png");
        $image->setAlt("votre annonce");

        $advert->setImage($image);

        $application1 = new Application();
        $application1->setAuthor('Denis');
        $application1->setContent('Je suis motivé et dynamique');

        $application2 = new Application();
        $application2->setAuthor('Christelle');
        $application2->setContent('Je suis motivé et dynamique et tres sociable');

        $listSkills = $em->getRepository('DAPlatformBundle:Skill')->findAll();

        foreach ($listSkills as $skill) {
            $adverSkill = new AdvertSkill();
            $adverSkill->setAdvert($advert);
            $adverSkill->setSkill($skill);

            $adverSkill->setLevel('expert');

            $em->persist($adverSkill);
        }
        $skill = new Skill();
        $skill2 = new Skill();

        $skill->setName('php');
        $skill2->setName('javascript');

        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        $em->persist($advert);

        $em->persist($application1);
        $em->persist($application2);

        $em->flush();

        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashbag()->add('notice', 'Annonce bien enregistrée');

            return $this->redirectToRoute('da_platform_view', array('id' => 5));
        }
        return $this->render('DAPlatformBundle:Advert:add.html.twig');
    }

    public function deleteAction()
    {
        return $this->render('DAPlatformBundle:Advert:delete.html.twig');
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('DAPlatformBundle:Advert')->find($id);

        if ($advert === null) {
            throw new NotFoundHttpException('L\'annonce avec d\'id "' . $id . '" n\existe pas.');
        }
        
        $listCategories = $em->getRepository('DAPlatformBundle:Category')->findAll();

        foreach ($listCategories as $category) {
            $advert->addCategory($category);
        }

        $em->flush();
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashbag()->add('notice', 'Annonce bien modifiée');

            return $this->redirectToRoute('da_platform_view', array('id' => 5));
        }

        return $this->render('DAPlatformBundle:Advert:edit.html.twig', array(
            'advert'=>$advert,
            'listCategories'=>$listCategories,
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
