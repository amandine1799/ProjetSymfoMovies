<?php

namespace App\Controller;

use App\Entity\Actors;
use App\Form\ActorsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/actors", name="actors_")
*/
class ActorsController extends AbstractController
{
    
    /**
     * @Route("/new", name="new_actor", methods={"GET","POST"})
     */
    public function new(Request $request)
    {
        $actor = new Actors();
        $form = $this->createForm(ActorsType::class, $actor);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($actor);
            $entityManager->flush();
            
            return $this->redirectToRoute('actors_show', ['name' => $actor->getName()]);
        }

        return $this->render('actors/new.html.twig', [
            'form' => $form->createView(),
            ]);
        }
        
        /**
         * @Route("/{name}", name="show") 
         */
        public function actor(Actors $actor)
        {
            return $this->render('actors/index.html.twig', [
                'actor' => $actor
            ]);
        }
}


