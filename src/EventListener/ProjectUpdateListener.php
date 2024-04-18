<?php 

namespace App\EventListener;

use App\Entity\Project;
use App\DoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html#entity-listeners-class
 *
 * Declaration in the entity:
 *
 * @ORM\EntityListeners({"App\Doctrine\Listener\UserListener"})
 */
final class ProjectUpdateListener
{
    private $oldUsers;

    /**
     * @see https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html#onflush
     */
    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $em = $event->getObjectManager();
        $project = $event->getObject();
        $uow = $em->getUnitOfWork();
        $uow->computeChangeSets($em->getClassMetadata(Project::class), $project);

        $originalData = $uow->getOriginalEntityData($project);
        dd($originalData['users'], $project->getUsers()->toArray());
        dd($project);
        dd($em->getUnitOfWork());
        $this->oldUsers =  $uow;
    }

    /**
     * @see https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html#preflush
     */
    
    
}