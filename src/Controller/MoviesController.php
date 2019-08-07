<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ActorsRepository;
use App\Repository\GenresRepository;

/**
 * @Route("/movies", name="movies_")
 */
class MoviesController extends AbstractController
{


    /**
     * @Route("/", name="index")
     */
    public function index(MediaRepository $repo, GenresRepository $genresrepo, Request $request)
    {

      $medias = $repo->findAll();
      $genres = $genresrepo->findAll();
      $deceniesmin = $repo->findOneBy(
        [],
        ['released_year' => 'ASC'],
        1
      );
      $deceniesmin = $deceniesmin->getReleasedYear();
      $deceniesmin = substr($deceniesmin, 0, -1).'0';
      $deceniesmin = intval($deceniesmin);

      $deceniesmax = $repo->findOneBy(
        [],
        ['released_year' => 'DESC'],
        1
      );
      $deceniesmax = $deceniesmax->getReleasedYear();
      $deceniesmax = substr($deceniesmax, 0, -1).'0';
      $deceniesmax = intval($deceniesmax);
        if ($request->isMethod('post')){
            $genre_id = $request->request->get('genre');
            $genre = $genresrepo->find($genre_id);
            $decenie = $request->request->get('decade');
            $type = $request->request->get('type');
            $medias = $repo->findBySearch($genre, $decenie, $type);
        }
            
        return $this->render('movies/index.html.twig', [
            'medias' => $medias,
            'genres' => $genres,
            'min' => $deceniesmin,
            'max' => $deceniesmax
        ]);
}

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, ActorsRepository $actorsRepository)
    {
        $medium = new Media();
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('media');
            foreach($data['actors'] as $user_id) 
            {
                $user = $actorsRepository->find($user_id);
                $medium->addActor($user);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($medium);
            $entityManager->flush();

            return $this->redirectToRoute('movies_crud');
        }

        return $this->render('movies/new.html.twig', [
            'medium' => $medium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{title}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Media $medium, ActorsRepository $actorsRepository)
    {
        new Media();
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('media');
            foreach($data['actors'] as $user_id)
            {
                $user = $actorsRepository->find($user_id);
                $medium->addActor($user);
            }
            $this->getDoctrine()->getManager()->persist($medium);
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
