<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationType;
use App\Controller\NotificationsController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(NotificationsController $notif, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        $user = new Users();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            $notif->notify($user);

            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig', [
            'error' => $error
        ]);
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){}
}
