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
        // Affiche tous les media de base (Quand on arrive sur la page d'accueil ou quand on recherche "tout")
        $medias = $repo->findAll();

        // Récupération de tous les genres pour le formulaire
        $genres = $genresrepo->findAll();
        // Récupération de la date la plus basse
        $deceniesmin = $repo->findOneBy(
            [],
            ['released_year' => 'ASC'],
            1
        );
        // Appel de la fonction en bas pour arrondir l'année à XXX0
        $deceniesmin = $this->decenies($deceniesmin);

        // Récupération de la date la plus haute
        $deceniesmax = $repo->findOneBy(
        [],
        ['released_year' => 'DESC'],
        1
        );
        // Appel de la fonction en bas (la meme) pour arrondir l'année à XXX0
        $deceniesmax = $this->decenies($deceniesmax);

        $genre_id = 0;
      
        // Gestion des filtres
        if ($request->isMethod('post')){
            // Récupération du genre entré dans le formulaire
            $genre_id = $request->request->get('genre');
            
            // Récupération de la décenie entrée dans le formulaire
            $decenie = $request->request->get('decade');

            // Récupération du type entrée dans le formulaire 
            $type = $request->request->get('type');

            // S'il y a un genre de sélectionné alors on recupère le genre dans la bdd
            if($genre_id != 0){
                $genre = $genresrepo->find($genre_id);
            }
            // Sinon on le mets à null
            else{
                $genre = null;
            }
            
            // S'il n'y a pas de décenie selectionnée alors on le mets à null
            if($decenie == 0){
                $decenie = null;
            } 

            // S'il n'y a pas de type selectionné alors on le met à null
            if($type != "Série" && $type != "Film") {
                $type = null;
            } 

            // Appel de la fonction dans le MediaRepository suivant ce qu'on a donné
            $medias = $repo->findBySearch($genre, $type, $decenie);
        }
            
        return $this->render('movies/index.html.twig', [
            'genre_id' => $genre_id,
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

            return $this->redirectToRoute('movies_crud');
        }

        return $this->render('movies/new.html.twig', [
            'media' => $media,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{title}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Media $media, ActorsRepository $actorsRepository)
    {
        new Media();
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

        return $this->render('movies/edit.html.twig', [
            'media' => $media,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{title}/delete", name="delete")
     */
    public function delete(Request $request, Media $media)
    {
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($media);
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
    public function film(Media $media)
    {
        return $this->render('movies/media.html.twig', [
            'media' => $media,
        ]);
    }

    /**
     * Fonction qui arrondi l'année au 0 le plus bas (ex. : 1996 -> 1990)
     */
    public function decenies($decenies)
    {
        $res = strval($decenies->getReleasedYear());
        $res = substr($res, 0, -1).'0';
        $res = intval($res);

        return $res;
    }
}
