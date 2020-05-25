<?php

namespace App\EventListener;

use DateTime;
use App\Entity\Project;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ProjectListener
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $event)
    {

        $entity = $event->getObject();

        if (!$entity instanceof Project) {
            return;
        }

        $user = $this->security->getUser();
        $entity->setCreatedAt(new DateTime())
                ->setOwner($user);
    }
}
