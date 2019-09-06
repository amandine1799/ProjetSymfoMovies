<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Entity\MediaUsers;
use App\Repository\MediaRepository;
use App\Repository\ActorsRepository;
use App\Repository\GenresRepository;
use App\Repository\MediaUsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MediaController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function home(MediaRepository $repo)
    {
        $medias = $repo->nextReleased();
        $last = $repo->lastReleased();
        return $this->render('medias/home.html.twig', [
            'medias' => $medias,
            'last' => $last
        ]);
    }

    /**
     * @Route("/medias", name="medias.index")
     */
    public function index(MediaRepository $repo, GenresRepository $genresrepo, Request $request)
    {
        // Remplissage des valeurs du formulaire
        $genres = $genresrepo->findAll();

        $decades = $repo->getDistinctDecades();

        // Recherche des films en fonction des critères
        $genre_id = null;
        $type = null;
        $decade = null;

        if ($request->isMethod('post')) {
            $genre_id = $request->request->get('genre');
            $decade = $request->request->get('decade');
            $type = $request->request->get('type');
            
            if($decade == 0) {
                $decade = null;
            }

            if($genre_id == 0) {
                $genre_id = null;
            }

            if($type != 1 && $type != 2) {
                $type = null;
            }
        }

        $medias = $repo->findWithFilters($genre_id, $type, $decade);
        
        
        return $this->render('medias/index.html.twig', [
            'genre_id' => $genre_id,
            'type' => $type,
            'decade' => $decade,
            'medias' => $medias,
            'genres' => $genres,
            'decades' => $decades
        ]);
    }

    /**
     * @Route("/aleatoire", name="aleatoire")
     */
    public function aleatoire(MediaRepository $rep){
        // Ajax calling
        $render = $this->render('medias/aleatoire.html.twig',[
            'mediaaleatoire' => $rep->aleatoireMedias()
        ]);
        $response = [
            "code"   => 200,
            "render" => $render->getContent(),
        ];
        return new JsonResponse($response);
    }

    /**
     * @Route("/medias/new", name="medias.new", methods={"GET","POST"})
     */
    public function new(Request $request, ActorsRepository $actorsRepository)
    {
        $media = new Media();
        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $request->request->get('media');
            foreach($data['actors'] as $actor_id) 
            {
                $actor = $actorsRepository->find($actor_id);
                $media->addActor($actor);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($media);
            $entityManager->flush();

            return $this->redirectToRoute('medias.crud');
        }

        return $this->render('medias/new.html.twig', [
            'media' => $media,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/medias/{id}/edit", name="medias.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Media $media, ActorsRepository $actorsRepository)
    {
        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('media');
            foreach($data['actors'] as $actor_id)
            {
                $actor = $actorsRepository->find($actor_id);
                $media->addActor($actor);
            }
            $this->getDoctrine()->getManager()->persist($media);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('medias/edit.html.twig', [
            'media' => $media,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/medias/{id}/delete", name="medias.delete")
     */
    public function delete(Request $request, Media $media)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($media);
        $entityManager->flush();
        return $this->redirectToRoute('medias.crud');
    }

    /**
     * @Route("/medias/crud", name="medias.crud", methods={"GET"})
     */
    public function crud(MediaRepository $mediaRepository)
    {
        return $this->render('medias/crud.html.twig', [
            'media' => $mediaRepository->findAll('title'),
        ]);
    }

    /**
     * @Route("/medias/{id}/haveseen", name="medias.haveseen")
     */
    public function haveseen(Media $media, ObjectManager $manager, MediaUsersRepository $repo)
    {
        $user = $this->getUser();
        $mediauser = $repo->findOneBy(['media' => $media, 'users' => $user]);
        if($mediauser == null){
            $mediauser = new MediaUsers();
        }
        $mediauser->setMedia($media);
        $mediauser->setUsers($user);
        if($mediauser->getHaveSeen()==true){
            $mediauser->setHaveSeen(false);
            $active = false;
        } else {
            $mediauser->setHaveSeen(true);
            $active = true;
        }

        $manager->persist($mediauser);
        $manager->flush();

        $response = [
            "active" => $active
        ];

        return new JsonResponse($response);
    }

    /**
     * @Route("/medias/dejavu", name="medias.dejavu")
     */
    public function listHaveSeen(MediaUsersRepository $repo)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('medias/dejavu.html.twig', [
            'medias' => $repo->findBy([
                'users' =>  $this->getUser(),
                'haveSeen' => true
            ])
        ]);
    }

    /**
     * @Route("/medias/{id}/wantsee", name="medias.wantsee")
     */
    public function wantsee(Media $media, ObjectManager $manager, MediaUsersRepository $repo)
    {
        $user = $this->getUser();
        $mediauser = $repo->findOneBy(['media' => $media, 'users' => $user]);
        if($mediauser == null){
            $mediauser = new MediaUsers();
        }
        $mediauser->setMedia($media);
        $mediauser->setUsers($user);
        if($mediauser->getWishList()==true){
            $mediauser->setWishList(false);
            $active = false;
        } else {
            $mediauser->setWishList(true);
            $active = true;
        }

        $manager->persist($mediauser);
        $manager->flush();

        $response = [
            "active" => $active
        ];

        return new JsonResponse($response);
    }

    /**
     * @Route("/medias/wishlist", name="medias.wishlist")
     */
    public function wishList(MediaUsersRepository $repo)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('medias/wishlist.html.twig', [
            'medias' => $repo->findBy([
                'users' => $this->getUser(),
                'wishList' => true
            ])
        ]);
    }

    /**
    * @Route("/medias/{id}", name="medias.show")
    */
    public function show(Media $media)
    {
        return $this->render('medias/media.html.twig', [
            'media' => $media,
        ]);
    }
}
