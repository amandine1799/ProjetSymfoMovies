<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/movies", name="movies_")
 */
class MoviesController extends AbstractController
{


    /**
     * @Route("/", name="index")
     */
    public function index(MediaRepository $repo)
    {
      $medias = $repo->findAll();

        return $this->render('movies/index.html.twig', [
            'medias' => $medias,
        ]);
}

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request)
    {
        $medium = new Media();
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($medium);
            $entityManager->flush();

            return $this->redirectToRoute('movies');
        }

        return $this->render('movies/new.html.twig', [
            'medium' => $medium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{title}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Media $medium)
    {
        new Media();
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('movies_crud');
        }

        return $this->render('movies/edit.html.twig', [
            'medium' => $medium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{title}/delete", name="delete")
     */
    public function delete(Request $request, Media $medium)
    {
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($medium);
            $entityManager->flush();
      

        return $this->redirectToRoute('movies_crud');
    }

    /**
     * @Route("/crud", name="crud", methods={"GET"})
     */
    public function admin(MediaRepository $mediaRepository)
    {
        return $this->render('movies/crud.html.twig', [
            'media' => $mediaRepository->findAll(),
        ]);
    }

    /**
    * @Route("/{title}", name="show")
    */
    public function film(Media $media) {
      return $this->render('movies/media.html.twig', [
        'media' => $media
      ]);
    }
}
