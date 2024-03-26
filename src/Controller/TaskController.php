<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TaskType;
use App\Repository\StatusRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    public function __construct(private TaskRepository $taskRepository, private ProjectRepository $projectRepository, private StatusRepository $statusRepository, private EntityManagerInterface $entityManager){
    }

    #[Route('/project/{id}/task/add', name: 'app_task_add')]
    public function addTask(Request $request, int $id): Response {
        $project = $this->projectRepository->find($id);
        $status = $this->statusRepository->findAll();
        $users = $project->getUsers();

        $task = new Task();
        $task->setProject($project);

        $form = $this->createForm(TaskType::class, $task, [
            'users' => $users,
            'status' => $status,
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project', ['id' => $project->getId() ]);
        }

        return $this->render('task/add-task.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/project/{projectId}/task/{taskId}/edit', name: 'app_task_edit')]
    public function editTask(Request $request, int $taskId): Response {
        $task = $this->taskRepository->find($taskId);
        $project = $task->getProject();
        $status = $this->statusRepository->findAll();
        $users = $project->getUsers();
        dump($taskId, $users);

        $form = $this->createForm(TaskType::class, $task, [
            'users' => $users,
            'status' => $status,
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project', ['id' => $project->getId()]);
        }

        return $this->render('task/edit-task.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/project/{projectId}/task/{taskId}/delete', name: 'app_task_delete')]
    public function deleteTask(int $taskId): Response{
        $task = $this->taskRepository->find($taskId);
        $project = $task->getProject();

        if(!$task){
            return $this->redirectToRoute('app_project', ['id' => $project->getId()]);
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_project', ['id' => $project->getId()]);
    }
}