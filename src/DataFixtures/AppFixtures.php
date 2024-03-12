<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Task;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Ajout des utilisateurs
        $users = [
            ['first_name' => 'Nathalie', 'last_name' => 'Dillon', 'email' => 'nathalie@driblet.com', 'join_on' => new \DateTime('2019-06-14'), 'status' => 'CDI'],
            ['first_name' => 'Demi', 'last_name' => 'Baker', 'email' => 'baker@driblet.com', 'join_on' => new \DateTime('2019-06-14'), 'status' => 'CDD'],
            ['first_name' => 'Marie', 'last_name' => 'Dupont', 'email' => 'marie@driblet.com', 'join_on' => new \DateTime('2019-06-14'), 'status' => 'Freelance']
        ];
        $usersReferences = [];

        foreach ($users as $userData) {
            $user = new User();
            $user->setFirstName($userData['first_name']);
            $user->setLastName($userData['last_name']);
            $user->setEmail($userData['email']);
            $user->setJoinOn($userData['join_on']);
            $user->setStatus($userData['status']);

            $manager->persist($user);

            $usersReferences[] = $user;
        }

        // Ajout des projets
        $projects = [
            ['name' => 'Tasklinker', 'archived' => false],
            ['name' => 'Site Vitrine Les soeurs marchands', 'archived' => false]
        ];
        $projectsReferences = [];

        foreach ($projects as $projectData) {
            $project = new Project();
            $project->setName($projectData['name']);
            $project->setArchived($projectData['archived']);

            $manager->persist($project);

            $projectsReferences[] = $project;
        }

        // Ajout des tâches
        $tasks = [
            ['name' => 'Gestion des droits des accès', 'description' => 'Un employé ne peut accéder qu\'à ses projets', 'deadline' => new \DateTime('2023-09-22'), 'status' => 'To Do', 'user' => null, 'project' => $projectsReferences[0]],
            ['name' => 'Developpement de la page employée', 'description' => 'Ouais', 'deadline' => new \DateTime('2001-01-01'), 'status' => 'Doing', 'user' => $usersReferences[1], 'project' => $projectsReferences[0]],
            ['name' => 'Developpement de la structure globale', 'description' => 'Non', 'deadline' => new \DateTime('2002-02-02'), 'status' => 'Done', 'user' => $usersReferences[1], 'project' => $projectsReferences[0]],
            ['name' => 'Developpement de la page projet', 'description' => 'Oui', 'deadline' => new \DateTime('2003-03-03'), 'status' => 'Done', 'user' => $usersReferences[0], 'project' => $projectsReferences[0]]
        ];

        foreach ($tasks as $taskData) {
            $task = new Task();
            $task->setName($taskData['name']);
            $task->setDescription($taskData['description']);
            $task->setDeadline($taskData['deadline']);
            $task->setStatus($taskData['status']);
            $task->setUser($taskData['user']);
            $task->setProject($taskData['project']);

            $manager->persist($task);
        }

        $manager->flush();
    }
}
