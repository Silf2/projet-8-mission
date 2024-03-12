<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{

    public function __construct(private ProjectRepository $projectRepository){
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
}
