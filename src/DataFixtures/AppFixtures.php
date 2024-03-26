<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Task;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++){
            $project = new Project();
            $project->setName('projet #' . $i);
            $project->setArchived(false);

            $manager->persist($project);
        }

        for ($i = 1; $i <= 15; $i++){
            $user = new User();
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setEmail($user->getFirstName() . '.' . $user->getLastName() . '@gmail.com');
            $user->setJoinOn(new \DateTime);
            $user->setStatus('CDI');

            $manager->persist($user);
        }

        $statuses = ['To Do', 'Doing', 'Done'];
        foreach($statuses as $statusName){
            $status = new Status();
            $status->setName($statusName);

            $manager->persist($status);
        }

        $manager->flush();
    }
}
