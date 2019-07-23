<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MediaRepository;
use App\Entity\Media;

class MoviesController extends AbstractController
{
    /**
     * @Route("/movies", name="movies")
     */
    public function index(MediaRepository $repo)
    {
      $medias = $repo->findAll();

        return $this->render('movies/index.html.twig', [
            'medias' => $medias,
        ]);
}
    /**
    * @Route("/", name="home")
    */
    public function home()
    {
        return $this->render('movies/home.html.twig', [
          'title' => "Bienvenue",
        ]);
    }

    /**
    * @Route("/movies/{title}", name="movies_film")
    */
    public function film(Media $media) {
      return $this->render('movies/film.html.twig', [
        'media' => $media
      ]);
    }
}
