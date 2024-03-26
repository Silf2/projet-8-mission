<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController{
    public function __construct(private UserRepository $userRepository, private EntityManagerInterface $entityManager){
    }

    #[Route('/users', name: 'app_users')]
    public function allUsers(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('user/user.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/{id}/edit', name: 'app_user_edit')]
    public function editUser(Request $request, int $id): Response {
        $user = $this->userRepository->find($id);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();

            return $this->redirectToRoute('app_users');
        }

        return $this->render('user/edit-user.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/user/{id}/delete', name: 'app_user_delete')]
    public function deleteUser(int $id): Response{
        $user = $this->userRepository->find($id);

        if(!$user){
            return $this->redirectToRoute('app_users');
        }

        foreach ($user->getTasks() as $task) {
            $this->entityManager->remove($task);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_users');
    }
}