<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarController extends AbstractController
{
    #[Route('/', name: 'app_cars_index')]
    public function index(CarRepository $carRepository): Response
    {
        $allCars = $carRepository->allCarsWithAllDetails();

        return $this->render('car/index.html.twig', [
            'data' => $allCars,
        ]);
    }

    #[Route('/show/{id}', name: 'app_show_car')]
    public function show($id, CarRepository $carRepository): Response
    {
        $theCar = $carRepository->singleCarWithAllDetails($id);

        return $this->render('car/index.html.twig', [
            'data' => $theCar,
        ]);
    }

    #[Route('/new', name: 'app_new_car')]
    public function new(Request $request): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $car = $form->getData();
            dd($car);
            // check if there is a seller with a phone number. If there is just add it, else make new seller

            // compare all data from drop-down menu with allowed values
        }

        return $this->render('car/new.html.twig', [
            'form' => $form,
        ]);
    }
}
