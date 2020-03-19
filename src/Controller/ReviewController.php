<?php

namespace App\Controller;

use App\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    /**
     * Affiche la liste de toutes les reviews
     * @Route("/reviews", name="review_index")
     */
    public function index()
    {
        return $this->render('review/index.html.twig', [
            'controller_name' => 'ReviewController',
        ]);
    }

    /**
     * Affiche le détail d'une review
     * @Route("/review/{review}", name="review_show", methods={"GET"}, requirements={"review"="\d+"})
     * @param Review $review
     */
    public function show(Review $review)
    {
    }

    /**
     * Traite la requête d'un formulaire de création de review
     * @Route("/review", name="review_create", methods={"POST"})
     */
    public function create()
    {
    }

    /**
     * Affiche le formulaire d'édition d'une review (GET)
     * Traite le formulaire d'édition d'une review (POST)
     * @Route("/review/{review}/edit", name="review_edit", methods={"GET", "POST"})
     * @param Review $review
     */
    public function edit(Review $review)
    {
    }

    /**
     * Supprime une review
     * @Route("/review/{review}", name="review_delete", methods={"DELETE"})
     * @param Review $review
     */
    public function delete(Review $review)
    {
    }
}
