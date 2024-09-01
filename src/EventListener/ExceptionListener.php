<?php

namespace App\EventListener;

use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

final class ExceptionListener extends AbstractController
{
    public function __construct( 
        protected RequestStack $requestStack,  
        protected UserRepository $userRepository, 
    )
    {
    }

    #[AsEventListener(event: KernelEvents::EXCEPTION, priority: -10)]
    public function onKernelException(ExceptionEvent $event): void
    {
        // Getting exception from ExceptionEvent object
        $exception = $event->getThrowable();

        $session = $this->requestStack->getSession();

        // Get the security data from the session
        $currentUserEmail = $session->get('_security.last_username');
        $user = $this->userRepository->findOneBy(['email' => $currentUserEmail]);
        $roles = $user->getRoles();

        // Checking if the exception is of NotFoundHttpException
        if ($exception instanceof NotFoundHttpException) {
            // Default template for all logged in users
            $template = 'error404_user.html.twig';
 
            // Checking if the user is logged in and his/her role         
            if (in_array('ROLE_ADMIN', $roles)) {
                $template = 'error404_admin.html.twig'; // Template za admina
            }

            // Generating response with an appropriate template
            $response = new Response();
            $response->setContent(
                $this->render($template, ['exception' => $exception, 'roles' => $roles, 'user' => $user])->getContent()
            );

            // Setting HTTP status code to 404
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            // Setting the response as Exception object
            $event->setResponse($response);
        }
    }
}