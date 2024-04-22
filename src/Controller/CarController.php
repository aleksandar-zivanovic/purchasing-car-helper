<?php

namespace App\Controller;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarController extends AbstractController
{
    #[Route('/', name: 'app_cars_index')]
    public function index(CarRepository $carRepository): Response
    {
        $allCars = $carRepository->allCarsWithAllDetails();

        return $this->render('car/index.html.twig', [
            'cars' => $allCars,
        ]);
    }
}
