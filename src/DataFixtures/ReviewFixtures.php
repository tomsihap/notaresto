<?php

namespace App\DataFixtures;

use App\Entity\Review;
use App\Repository\RestaurantRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    private $restaurantRepository;
    private $reviewRepository;
    private $userRepository;

    public function __construct(RestaurantRepository $restaurantRepository,
                                ReviewRepository $reviewRepository,
                                UserRepository $userRepository) {

        $this->restaurantRepository = $restaurantRepository;
        $this->reviewRepository = $reviewRepository;
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        /**
         * On créée 7000 reviews initiales
         */
        for ($i=0; $i<7000; $i++) {
            $review = new Review();
            $review->setMessage( $faker->text(800) );
            $review->setRating( rand(0,5) );
            $review->setRestaurant( $this->restaurantRepository->find(rand(1, 1000)) );
            $review->setUser( $this->userRepository->findOneBy(["email" => "client@notaresto.com"]) );
            $manager->persist($review);
        }

        /**
         * On les enregistre en DB
         */
        $manager->flush();


        /**
         * On créée 3000 reviews enfants (dont le parent est une des review initiales)
         */
        for ($i=0; $i<3000; $i++) {
            $review = new Review();
            $review->setMessage( $faker->text(800) );
            $review->setParent( $this->reviewRepository->find(rand(1, 7000)) ); // On cherche un ID entre 1 et 7000 (un commentaire initial)
            $review->setRestaurant( $review->getParent()->getRestaurant() ); // On récupère le restaurant de la review parente
            $review->setUser( $this->userRepository->findOneBy(["email" => "restaurateur@notaresto.com"]) );
            $manager->persist($review);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            RestaurantFixtures::class,
        );
    }
}
