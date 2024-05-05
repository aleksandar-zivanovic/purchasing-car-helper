<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Communication;
use App\Form\CommunicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/communication', name: 'app_communication')]
class CommunicationController extends AbstractController
{
    #[Route('/{car}/new', name: '_new')]
    public function new(Request $request, EntityManagerInterface $em, Car $car): Response
    {
        $communication = new Communication();
        $form = $this->createForm(CommunicationType::class, $communication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $communication->setCar($car);

            $em->persist($communication);
            $em->flush();

            $this->addFlash('new-communication-success', 'Congratulation! Communication with the seller is saved successfully.');

            return $this->redirectToRoute('app_show_car', ['id' => $car->getId()]);
        }

        return $this->render('/communication/new.html.twig', [
            'form' => $form,
        ]);
    }
}
