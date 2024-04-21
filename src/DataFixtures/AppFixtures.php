<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\Engine;
use App\Entity\Seller;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
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
        
        $manager->persist($engine);
        $manager->persist($seller);
        $manager->persist($car);

        $manager->flush();
    }
}
