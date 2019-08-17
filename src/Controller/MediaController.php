<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\ActorsRepository;
use App\Repository\GenresRepository;
use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class MediaController extends AbstractController
{
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
            'decades' => $decades,
        ]);
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
            foreach($data['actors'] as $user_id) 
            {
                $user = $actorsRepository->find($user_id);
                $media->addActor($user);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($media);
            $entityManager->flush();

            return $this->redirectToRoute('medias.crud');
        }

        return $this->render('medias/new.html.twig', [
            'media' => $media,
            'form' => $form->createView(),
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
            foreach($data['actors'] as $user_id)
            {
                $user = $actorsRepository->find($user_id);
                $media->addActor($user);
            }
            $this->getDoctrine()->getManager()->persist($media);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('medias/edit.html.twig', [
            'media' => $media,
            'form' => $form->createView(),
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
    * @Route("/medias/{id}", name="medias.show")
    */
    public function show(Media $media)
    {
        return $this->render('medias/media.html.twig', [
            'media' => $media,
        ]);
    }
}
