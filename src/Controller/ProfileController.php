<?php

namespace App\Controller;

use App\Form\AccountType;
use App\Repository\ReviewRepository;
use App\Repository\MediaUsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function profile(Request $request, ObjectManager $manager, MediaUsersRepository $repo, ReviewRepository $reporeview)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $img = $user->getImage();

        if(!$img) {
            $img = ($this->getParameter(('image_directory')).$user->getImage());
        }

        $formProfil = $this->createForm(AccountType::class, $user);
        $formProfil->handleRequest($request);

        if ($formProfil->isSubmitted() && $formProfil->isValid()) {

            $this->addFlash('success', 'Votre profil a bien été modifié!');

            if(!is_null($img)){
                $file = $formProfil->get('image')->getData();
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

                $file->move($this->getParameter('image_directory'), $fileName);
                $user->setImage($fileName);
            }
            else {
                $user->setImage($img);
            }

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/profile.html.twig', [
            'Formprofil' => $formProfil->createView(),
            'user' => $user,
            'image' => $img,
            'medias' => $repo->findBy([
                'users' => $this->getUser(),
                'wishList' => true
            ]),
            'media' => $repo->findBy([
                'users' =>  $this->getUser(),
                'haveSeen' => true
            ]),
            'reviews' => $reporeview->findBy([
                'user' =>  $this->getUser()
            ])
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}
