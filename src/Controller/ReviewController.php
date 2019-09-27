<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ReviewController extends AbstractController
{
    private $currentUser;

    public function __construct(Security $security, ReviewRepository $rr) {
        $this->currentUser = $security->getUser(); 
        $this->reviewRepository = $rr;
    }

    /**
     * @Route("/review/list", name="review.list")
     */
    public function list()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $reviews = $this->reviewRepository->getReviewsByUser($this->currentUser->getID());

        return $this->render('reviews/list.html.twig', [
            "reviews" => $reviews
        ]);
    }

    /**
     * @Route("/reviews/{id}/new", name="review.new", methods={"GET","POST"})
     */
    public function new(Request $request, Media $media)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $review = new Review();
        $review->setMedia($media);
        $review->setUser($this->currentUser);

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();
            
            return $this->redirectToRoute('medias.show', ['id' => $media->getID()]);
        }

        return $this->render('reviews/new.html.twig', [
            'review' => $review,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reviews/{id}/delete", name="review.delete")
     */
    public function delete(Review $review)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($review);
        $entityManager->flush();
        return $this->redirectToRoute('review.list');
    }    

    /**
     * @Route("/review/edit/{id}", name="review.edit")
     */
    public function edit(Review $review, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->getUser() != $review->getUser()){
            $media = $review->getMedia();
            return $this->redirectToRoute('medias.show', ["id" => $media->getID()]);
        }
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('review');
            $this->getDoctrine()->getManager()->persist($review);
            $this->getDoctrine()->getManager()->flush();
        }
        
        return $this->render('reviews/edit.html.twig', [
            'review' => $review,
            'form' => $form->createView(),
        ]);
    }
}
