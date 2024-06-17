<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Repository\SellerRepository;
use App\Service\SellerManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CarController extends AbstractController
{
    #[Route('/{order}', name: 'app_cars_index')]
    public function index(CarRepository $carRepository, string $order = 'DESC'): Response
    {
        $order = $order == "ASC" ? "ASC" : "DESC";
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        if($this->isGranted('ROLE_USER') && !$this->isGranted('ROLE_ADMIN')) {
            $userID = $this->getUser()->getID();
            $allCars = $carRepository->allUserCarsWithAllDetails(order:$order, userID:$userID);
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            $allCars = $carRepository->allCarsWithAllDetails($order);
        }

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
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em, SellerRepository $sr): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $car = $form->getData();
            $phone = $car->getSeller()->getPhone();
            
            // if there is not a seller with a specified phone number creat a new one
            $location = $car->getSeller()->getLocation();
            $service = new SellerManager(entityManager:$em, sellerRepository:$sr);
            $seller = $service->findOrCreateSeller(phone:$phone, location:$location);

            $car->setSeller($seller);
            $car->setUser($this->getUser());
            
            $em->persist($car);
            $em->flush();
            $this->addFlash('new-car-success', 'Congratulation! New car ad is saved successfully.');
            return $this->redirectToRoute('app_cars_index');
        }

        return $this->render('car/new.html.twig', [
            'form' => $form,
            'action' => 'delete',
        ]);
    }

    #[Route('/edit/{id}', name:'app_edit_car', requirements:['id' => '\d+'])]
    public function edit(
        $id, 
        EntityManagerInterface $em, 
        CarRepository $carRepository, 
        Request $request, 
        SellerRepository $sr
    ): Response
    {
        $car = $carRepository->find($id);
        $userId = $this->getUser()->getId();

        if ($car != null && ($userId ===  $car->getUser()->getId() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
            $form = $this->createForm(CarType::class, $car, ['submit_label' => 'edit']);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $car = $form->getData();
                $phone = $car->getSeller()->getPhone();
                $location = $car->getSeller()->getLocation();
    
                // if there is not a seller with a specified phone number creat a new one
                $service = new SellerManager(entityManager:$em, sellerRepository:$sr);
                $service->findOrCreateSeller(phone:$phone, location:$location);
                
                // removing sellers without cars if there are
                $removeSellers = $sr->findSellerIdsWithoutCars();

                if ($removeSellers) {
                    foreach($removeSellers as $sellerId) {
                        $exSeller = $sr->find($sellerId);
                        $em->remove($exSeller);
                    }
                }
                
                $em->persist($car);
                $em->flush();
                $this->addFlash('car-edit-success', 'Congratulation! You updated your ad successfully!');
                return $this->redirectToRoute('app_show_car', ['id' => $car->getId()]);
            }
        } else {
            $this->addFlash(
                type:'car-edit-failed', 
                message: 'This is not your ad. You dont have permission to edit it!',
            );
            return $this->redirectToRoute('app_cars_index');
        }

        return $this->render('car/new.html.twig', [
            'form' => $form,
            'action' => 'edit',
        ]);
    }

    #[Route('/delete/{id}', name:'app_delete_car', requirements:['id' => '\d+'])]
    public function delete($id, EntityManagerInterface $em, CarRepository $carRepository): Response
    {
        $car = $carRepository->find($id);
        $userId = $this->getUser()->getId();
        
        if ($car != null && ($userId ===  $car->getUser()->getId() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
            $em->remove($car);
            $em->flush();
            $this->addFlash(
                type:'car-delete-success', 
                message: 'Ad is removed from your list!');
        } elseif ($car == null) {
            $this->addFlash(
                type:'car-delete-failed', 
                message: 'Chosen ad doesn\'t exist!',
            );
        } elseif ($userId !== $car->getUser()->getId()){
            $this->addFlash(
                type:'car-delete-failed', 
                message: 'This is not your ad. You dont have permission to delete it!',
            );
        }
        
        return $this->redirectToRoute('app_cars_index');
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
