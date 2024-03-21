<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{

    public function __construct(private ProjectRepository $projectRepository, private UserRepository $userRepository, private EntityManagerInterface $entityManager){
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $projects = $this->projectRepository->findAll();

        return $this->render('project/home.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/projet/{id}', name : 'app_project')]
    public function project(int $id): Response {
        $project = $this->projectRepository->find($id);

        if(!$project){
            return $this->redirectToRoute('app_home');
        };

        return $this->render('project/project.html.twig',[
            'project' => $project,
        ]);
    }

    #[Route('/project/add', name : 'app_project_add')]
    public function addProject(Request $request): Response {
        $project = new Project();
        $project->setArchived(false);
        $users = $this->userRepository->findAll();


        $form = $this->createForm(ProjectType::class, $project, [
            'users' => $users,
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        
            $this->entityManager->persist($project);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project', ['id' => $project->getId() ]);
        }

        return $this->render('project/add-project.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/project/{id}/edit', name : 'app_project_edit')]
    public function editProject(Request $request, int $id) : Response {
        $project = $this->projectRepository->find($id);
        $users = $this->userRepository->findAll();

        $form = $this->createForm(ProjectType::class, $project, [
            'users' => $users,
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project', ['id' => $project->getId() ]);
        }

        return $this->render('project/edit-project.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/project/{id}/archive', name : 'app_project_archive')]
    public function archiveProject(int $id) : Response {
        $project = $this->projectRepository->find($id);
        $project->setArchived(true);

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
