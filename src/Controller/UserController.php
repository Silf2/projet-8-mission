<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController{
    public function __construct(private UserRepository $userRepository){
    }

    #[Route('/users', name: 'app_users')]
    public function allUsers(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('user.html.twig', [
            'users' => $users,
        ]);
    }
}