<?php

namespace App\Controller;

use App\Entity\Actors;
use App\Form\ActorsType;
use App\Repository\ActorsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/actors", name="actors_")
*/
class ActorsController extends AbstractController
{
    
    /**
     * @Route("/new", name="new", methods={"GET","POST"})
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
            
            return $this->redirectToRoute('actors_crud');
        }

        return $this->render('actors/new.html.twig', [
            'form' => $form->createView(),
            ]);
        }

    /**
     * @Route("/{name}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Actors $actor)
    {
        new Actors();
        $form = $this->createForm(ActorsType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('actors_crud');
        }

        return $this->render('actors/edit.html.twig', [
            'actor' => $actor,
            'form' => $form->createView(),
        ]);
    }

        /**
        * @Route("/{name}/delete", name="delete_actor")
        */
        public function delete(Request $request, Actors $actor)
        {
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($actor);
            $entityManager->flush();
      

        return $this->redirectToRoute('actors_crud');
        }
            
        /**
         * @Route("/crud", name="crud", methods={"GET"})
         */
        public function admin(ActorsRepository $actorsRepository)
        {
            return $this->render('actors/crud.html.twig', [
                'actor' => $actorsRepository->findAll(),
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


