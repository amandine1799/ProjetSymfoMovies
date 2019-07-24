<?php

namespace App\Controller;

use App\Entity\Actors;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActorsController extends AbstractController
{
    /**
     * @Route("/actors/{name}", name="actor_show") 
     */
    public function actor(Actors $actor)
    {
        return $this->render('actors/index.html.twig', [
            'actor' => $actor
        ]);
    }
}
