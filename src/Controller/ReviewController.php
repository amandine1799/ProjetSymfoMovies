<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class ReviewController extends AbstractController
{
    public function __construct(Security $security, ReviewRepository $rr) {
        $this->security = $security;
        $this->reviewRepository = $rr;
    }

    /**
     * @Route("/review/list", name="review.list")
     */
    public function list()
    {
        $user = $this->security->getUser();

        $reviews = $this->reviewRepository->getReviewsByUser($user->getID());

        return $this->render('reviews/list.html.twig', [
            "reviews" => $reviews
        ]);
    }

    /**
     * @Route("/review/edit/{id}", name="review.edit")
     */
    public function edit(Review $review, Request $request)
    {
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
