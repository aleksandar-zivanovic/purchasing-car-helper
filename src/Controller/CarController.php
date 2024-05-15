<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Seller;
use App\Form\CarType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarController extends AbstractController
{
    #[Route('/{order}', name: 'app_cars_index')]
    public function index(CarRepository $carRepository, string $order = 'DESC'): Response
    {
        $order = $order == "ASC" ? "ASC" : "DESC";
        $allCars = $carRepository->allCarsWithAllDetails($order);

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

    #[Route('/cars/{brand}-{model}-{engine}-{fuel}-{order}', name: 'app_cars_by_model', priority: 3)]
    public function carsByModel(
        CarRepository $carRepository, 
        string $brand, 
        string $model, 
        int $engine, 
        string $fuel, 
        ?string $order = NULL, 
    ):  Response
    {
        $order = $order == "DESC" ? "DESC" : "ASC";
        
        $cars = $carRepository->allCarsByModel($brand, $model, $engine, $fuel, $order);
        return $this->render('car/index.html.twig', [
            'data' => $cars,
        ]);
    }

    #[Route('/new', name: 'app_new_car', priority: 2)]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $car = $form->getData();
            $phone = $car->getSeller()->getPhone();
            $seller = $em->getRepository(Seller::class)->findOneBy(['phone' => $phone]);

            if ($seller) {
                $car->setSeller($seller);
            }

            $car->setUser = $this->getUser();
            
            $em->persist($car);
            $em->flush();
            $this->addFlash('new-car-success', 'Congratulation! New car ad is saved successfully.');
            return $this->redirectToRoute('app_cars_index');
        }

        return $this->render('car/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/cars-by-communication/{order}', name:'app_cars_with_communication', priority:4)]
    public function carsWithCommunication(CarRepository $carRepository, string $order = 'DESC'): Response 
    {
        $allCars = $carRepository->allCarsWithAllDetails($order, true);
        
        return $this->render('car/index.html.twig', [
            'data' => $allCars,
        ]);
    }
}
