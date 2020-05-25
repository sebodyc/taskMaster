<?php

namespace App\EventListener;

use DateTime;
use App\Entity\Project;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ProjectSubscriber implements EventSubscriber
{

    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,

        ];
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
