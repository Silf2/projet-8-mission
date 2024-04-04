<?php 

namespace App\EventListener;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

//#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Project::class)]
class ProjectUpdateListener
{
    public function preUpdate(Project $project, PreUpdateEventArgs $event)
    {
        $em = $event->getObjectManager();
        $changeset = $event->getEntityChangeSet();
        dd($changeset, $project->getUsers(), $event->hasChangedField('users'));

        if (isset($changeset['users'])){
            $oldUsers = $changeset['users'][0];
            $newUsers = $changeset['users'][1];

            $removedUsers = array_diff($oldUsers->toArray(), $newUsers->toArray());
            $tasks = $project->getTasks();

            foreach($tasks as $task){
                $taskUser = $task->getUser();

                if($taskUser !== null && in_array($taskUser, $removedUsers, true)){
                    $task->setUser(null);
                    $em->persist($task);
                }
            }
        }

        $em->flush();
    }
}