<?php

namespace App\Controller;

use App\Entity\Actors;
use App\Form\ActorsType;
use App\Repository\ActorsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ActorController extends AbstractController
{
    /**
     * @Route("/actors/new", name="actors.new", methods={"GET","POST"})
     */
    public function new(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $actor = new Actors();
        $form = $this->createForm(ActorsType::class, $actor);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($actor);
            $entityManager->flush();
            
            return $this->redirectToRoute('actors.crud');
        }

        return $this->render('actors/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/actors/{id}/edit", name="actors.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Actors $actor)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(ActorsType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('actors.crud');
        }

        return $this->render('actors/edit.html.twig', [
            'actor' => $actor,
            'form' => $form->createView(),
        ]);
    }

        /**
        * @Route("/actors/{id}/delete", name="actors.delete")
        */
        public function delete(Actors $actor)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($actor);
            $entityManager->flush();
            return $this->redirectToRoute('actors.crud');
        }
            
        /**
         * @Route("/actors/crud", name="actors.crud", methods={"GET"})
         */
        public function crud(ActorsRepository $actorsRepository)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('actors/crud.html.twig', [
                'actor' => $actorsRepository->findAll(),
            ]);
        }

        /**
         * @Route("/actors/{id}", name="actors.show")
         */
        public function show(Actors $actor)
        {
            return $this->render('actors/index.html.twig', [
                'actor' => $actor
            ]);
        }
}


