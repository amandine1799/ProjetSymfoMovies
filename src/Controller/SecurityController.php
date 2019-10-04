<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Form\RegistrationType;
use App\Repository\MediaRepository;
use App\Repository\UsersRepository;
use App\Repository\ActorsRepository;
use App\Repository\ReviewRepository;
use App\Repository\MediaUsersRepository;
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
     * @Route("/membres/{id}/edit", name="membres.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Users $users)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(UsersType::class, $users);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('membres.crud');
        }

        return $this->render('security/edit.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
        ]);
    }

        /**
        * @Route("/membres/{id}/delete", name="membres.delete")
        */
        public function delete(Users $users)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($users);
            $entityManager->flush();
            return $this->redirectToRoute('membres.crud');
        }

    /**
     * @Route("/membres/crud", name="membres.crud", methods={"GET"})
     */
    public function crud(UsersRepository $usersRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('security/crud.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/statistiques", name="statistiques")
     */
    public function stats(MediaUsersRepository $repoMu, UsersRepository $usersRepo, ReviewRepository $reviewsRepo, ActorsRepository $actorsRepo, MediaRepository $mediasRepo)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('security/stats.html.twig', [
            'users' => $usersRepo->findAll(),
            'actors' => $actorsRepo->findAll(),
            'medias' => $mediasRepo->findAll(),
            'reviews' => $reviewsRepo->findAll(),
            'wishlist' => $repoMu->findBy([
                'wishList' => true
            ]),
            'seen' => $repoMu->findBy([
                'haveSeen' => true
            ]),
            'films' => $mediasRepo->findBy([
                'type' => 1
            ]),      
            'series' => $mediasRepo->findBy([
                'type' => 2
            ])
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
