<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/admin/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
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
}
