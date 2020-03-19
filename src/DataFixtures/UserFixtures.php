<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\CityRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;
    private $cityRepository;

    public function __construct(UserPasswordEncoderInterface $encoder, CityRepository $cityRepository)
    {
        $this->encoder = $encoder;
        $this->cityRepository = $cityRepository;
    }

    public function load(ObjectManager $manager)
    {

        $randomCity = $this->cityRepository->find(rand(1, 1000));

        $userAdmin = new User();
        $userAdmin->setEmail('moderateur@notaresto.com');
        $userAdmin->setPassword($this->encoder->encodePassword($userAdmin, 'notaresto'));
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setCity($randomCity);
        $manager->persist($userAdmin);

        $userClient = new User();
        $userClient->setEmail('client@notaresto.com');
        $userClient->setPassword($this->encoder->encodePassword($userAdmin, 'notaresto'));
        $userClient->setRoles(['ROLE_CLIENT']);
        $userClient->setCity($randomCity);
        $manager->persist($userClient);

        $userRestaurateur = new User();
        $userRestaurateur->setEmail('restaurateur@notaresto.com');
        $userRestaurateur->setPassword($this->encoder->encodePassword($userAdmin, 'notaresto'));
        $userRestaurateur->setRoles(['ROLE_RESTO']);
        $userRestaurateur->setCity($randomCity);
        $manager->persist($userRestaurateur);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CityFixtures::class,
        );
    }
}
