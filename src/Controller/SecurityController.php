<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistartionType;
use Symfony\Component\HttpFoundation\Request;
// use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/registration", name="security_registration")
     */
    public function registration(Request $request, UserPasswordEncoderInterface $encoder)
    {   
        $user = new User();
        $form = $this->createForm(RegistartionType::class, $user);
        
        $form->handleRequest($request);
        $manager = $this->getDoctrine()->getManager();

        // dump($user);
        if ( $form->isSubmitted() && $form->isValid() ) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(Request $request)
    {   
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {   
        // return $this->render('security/login.html.twig');
    }
}
