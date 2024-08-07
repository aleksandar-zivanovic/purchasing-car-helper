<?php

namespace App\Controller;

use App\Entity\Seller;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;
use App\Form\AdminSellerEditType;
use App\Form\AdminUserEditType;
use App\Repository\CarRepository;
use App\Repository\SellerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    /** 
     * Users administration
     */
    #[Route('/admin/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/edit/{id}', name: 'app_admin_edit_user', requirements:['id' => '\d+'])]
    public function editUser(User|null $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($user != null && !in_array(needle:'ROLE_ADMIN', haystack:$user->getRoles())) {
            $form = $this->createForm(AdminUserEditType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user = $form->getData();
                $em->persist($user);
                $em->flush();

                $this->addFlash('edit-success', 'User details are updated successfuly!');
                return $this->redirectToRoute('app_admin_users');
            }
        } else {
            $this->addFlash('edit-failed', 'User doesn\'t exist or you don\'t have a privilege to edit this user\'s details!');
            return $this->redirectToRoute('app_admin_users');
        }
        
        return $this->render('admin/users.edit.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/admin/users/delete/{id}', name: 'app_admin_delete_user', requirements:['id' => '\d+'])]
    public function deleteUser(User|null $user, EntityManagerInterface $em): Response
    {
        if ($user != null && !in_array(needle:'ROLE_ADMIN', haystack:$user->getRoles())) {
            $em->remove($user);
            $em->flush();
            $this->addFlash(
                type:'delete-success', 
                message: 'User is deleted!',
            );
        } elseif($user != null && in_array(needle:'ROLE_ADMIN', haystack:$user->getRoles())) {
            $this->addFlash(
                type:'delete-failed', 
                message: 'You can\'t delete an administrator!',
            );
        } elseif ($user == null) {
            $this->addFlash(
                type:'delete-failed', 
                message: 'This user doesn\'t exist!',
            );
        }

        return $this->redirectToRoute('app_admin_users');
    }

    /** 
     * Cars administration
     */
    #[Route('/admin/cars', name: 'app_admin_cars')]
    public function cars(CarRepository $carRepository): Response
    {
        $cars = $carRepository->allUserCarsWithAllDetails();

        return $this->render('admin/cars.html.twig', [
            'cars' => $cars,
        ]);
    }

    /** 
     * Sellers administration
     */
    #[Route('/admin/sellers', name: 'app_admin_sellers')]
    public function sellers(SellerRepository $sellerRepository): Response
    {
        $sellers = $sellerRepository->findAllSellersWithCarIds();

        return $this->render('admin/sellers.html.twig', [
            'sellers' => $sellers,
        ]);
    }
    
    #[Route('/admin/sellers/edit/{id}', name: 'app_admin_edit_seller', requirements:['id' => '\d+'])]
    public function editSeller(Seller|null $seller, Request $request, EntityManagerInterface $em): Response
    {
        if($seller != null) {
            $form = $this->createForm(AdminSellerEditType::class, $seller);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                $seller = $form->getData();
                $em->persist($seller);
                $em->flush();

                $this->addFlash('edit-success', 'Seller details are updated successfuly!');
                return $this->redirectToRoute('app_admin_sellers');
            }    
        } else {
            $this->addFlash('edit-failed', 'The seller details update failed!');
            return $this->redirectToRoute('app_admin_sellers');
        }

        return $this->render('admin/sellers.edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/users/sellers/{id}', name: 'app_admin_delete_seller', requirements:['id' => '\d+'])]
    public function deleteSeller(Seller|null $seller, EntityManagerInterface $em): Response
    {
        if ($seller != null) {
            $em->remove($seller);
            $em->flush();
            $this->addFlash(
                type:'delete-success', 
                message: 'Seller is deleted!',
            );
        } else {
            $this->addFlash(
                type:'delete-failed', 
                message: 'This seller doesn\'t exist!',
            );
        }

        return $this->redirectToRoute('app_admin_sellers');
    }
}
