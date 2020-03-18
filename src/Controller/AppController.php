<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Restaurant;
use App\Entity\Review;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app_index", methods={"GET"})
     */
    public function index()
    {

        $tenBestRestaurantsId = $this->getDoctrine()->getRepository(Review::class)->findBestTenRatings();

        $tenBestRestaurants = array_map(function($data) {
            return $this->getDoctrine()->getRepository(Restaurant::class)->find($data['restaurantId']);
        }, $tenBestRestaurantsId);

        return $this->render('app/index.html.twig', [
            'restaurants' => $tenBestRestaurants,
        ]);
    }

    /**
     * @Route("/search", name="app_search", methods={"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request, PaginatorInterface $paginator) {

        // On récupère l'input de recherche du formulaire, le name=zipcode
        $searchZipcode = $request->query->get('zipcode');


        // On recherche une ville par son code postal
        $city = $this->getDoctrine()->getRepository(City::class)->findOneBy(["zipcode" => $searchZipcode]);


        // Si une ville est trouvée
        if ($city) {

            $data = $city->getRestaurants();

            $restaurants = $paginator->paginate(
                $data,
                $request->query->getInt('page', 1),
                50
            );

            return $this->render('restaurant/index.html.twig', [
                'restaurants' => $restaurants,
            ]);
        }

        // Sinon, on redirige en page d'accueil

        return $this->redirectToRoute("app_index");

    }
}
