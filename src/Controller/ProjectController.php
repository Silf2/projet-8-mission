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

        return $this->render('home.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/projet/{id}', name : 'app_project')]
    public function project(int $id): Response {
        $project = $this->projectRepository->find($id);

        if(!$project){
            return $this->redirectToRoute('app_home');
        };

        return $this->render('project.html.twig',[
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
            $selectedUsers = $form->get('users')->getData();

            foreach ($selectedUsers as $user) {
                $project->addUser($user);
            }
        
            $this->entityManager->persist($project);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project', ['id' => $project->getId() ]);
        }

        return $this->render('add-project.html.twig', [
            'form' => $form->createView()
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
