<?php

namespace App\Controller;

use App\Form\AccountType;
use App\Form\EditImgType;
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

        $formImg = $this->createForm(EditImgType::class, $user);
        $form = $this->createForm(AccountType::class, $user);
    
        $formImg->handleRequest($request);
        $form->handleRequest($request);


        if ($formImg->isSubmitted() && $formImg->isValid()) {

            $img = $formImg->get('image')->getData();

            if($img != null)
            {
                $file = $formImg->get('image')->getData();
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

        if($form->isSubmitted()){
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'Le profil a bien été mis à jour.'
            );
        }

        return $this->render('profile/profile.html.twig', [
            'form' => $form->createView(),
            'formImg' => $formImg->createView(),
            'user' => $user,
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
