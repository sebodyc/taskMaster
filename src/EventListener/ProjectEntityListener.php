<?php

namespace App\EventListener;

use DateTime;
use App\Entity\Project;


use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ProjectEntityListener
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public function getSubscribedEvents()
    {
    }

    public function prePersist(Project $project, LifecycleEventArgs $event)
    {

        $user = $this->security->getUser();
        $project->setCreatedAt(new DateTime())
                ->setOwner($user);
    }
}
