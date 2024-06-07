<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\Communication;
use App\Entity\Engine;
use App\Entity\Seller;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('email@example.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setPassword('$2y$13$CNExZFhxFrrCeVHm1TK9Zu/kG4MOYskaPNFCrWWMu9TpFMzOoST5S');
        $user->setRoles(["ROLE_USER"]);

        $car = new Car();
        $car->setBrand('Peugeot');
        $car->setModel('307');
        $car->setBodyType('Hatchback');
        $engine = new Engine();
        $engine->setFuelType('Diesel');
        $engine->setEngineDisplacement(1997);
        $engine->setPowerKW(100);
        $engine->setPowerHP(136);
        $car->setEngine($engine);
        $date = \DateTimeImmutable::createFromFormat("Y-m", '2024-08');
        $car->setRegistrationExpirationDate($date);
        $car->setDescription("Cena je takva ima da se odradi eger ventil i ispod te cene ne idem");
        $car->setImage("https://gcdn.polovniautomobili.com/user-images/thumbs/2369/23698390/f70b3a74d793-1920x1080.jpg");
        $car->setUrl("https://www.polovniautomobili.com/auto-oglasi/23698390/peugeot-307-20-tdi");
        $car->setComment("Ovo je komentar auta!");
        $seller = new Seller();
        $seller->setlocation("Inđija");
        $seller->setPhone("0691997660");
        $car->setSeller($seller);
        $car->setPrice(1600);
        $car->setUser($user);

        $communication1 = new Communication();
        $communicationDate1 = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $communication1->setDate($communicationDate1);
        $communication1->setCar($car);
        $communication1->setComment("Ovo je lazni komentar koji sam dodao iz AppFixtures. On treba da prestavlja fejkovanu komunikacjiu sa prodavcem. Ovo je polje u koje unosite ono sto zelite u vezi komunikacije sa prodavcem konkretnog auta.");

        $communication2 = new Communication();
        $communicationDate2 = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $communication2->setDate($communicationDate2);
        $communication2->setCar($car);
        $communication2->setComment("Ovo je DRUGI lazni komentar koji sam dodao iz AppFixtures");
        
        $manager->persist($communication1);
        $manager->persist($communication2);
        $manager->persist($engine);
        $manager->persist($seller);
        $manager->persist($user);
        $manager->persist($car);

        $manager->flush();
    }
}
