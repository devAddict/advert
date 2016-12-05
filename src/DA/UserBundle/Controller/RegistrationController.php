<?php

namespace DA\UserBundle\Controller;

use DA\UserBundle\Form\UserType;
use DA\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this
                ->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('da_core_index');
        }

        return $this->render(
            'DAUserBundle:Registration:register.html.twig',
            array('form' => $form->createView())
        );
    }
}