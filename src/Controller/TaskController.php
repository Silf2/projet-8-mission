<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Project;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    public function __construct(private TaskRepository $taskRepository, private ProjectRepository $projectRepository, private EntityManagerInterface $entityManager){
    }

    #[Route('/project/{id}/task/add', name: 'app_task_add')]
    public function addTask(Request $request, int $id): Response {
        $project = $this->projectRepository->find($id);
        $users = $project->getUsers();

        $task = new Task();
        $task->setProject($project);

        $form = $this->createForm(TaskType::class, $task, [
            'users' => $users,
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project', ['id' => $project->getId() ]);
        }

        return $this->render('add-task.html.twig', [
            'form' => $form->createView()
        ]);
    }

}