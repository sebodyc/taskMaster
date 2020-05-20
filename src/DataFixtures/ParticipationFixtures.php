<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Project;
use App\Entity\Participation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ParticipationFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ProjectFixtures::class
        ];
    }


    public function loadData(ObjectManager $manager)
    {

        $this->createMany(Participation::class, 10, function (Participation $participation) {

            $role = 'VIEWER';

            if ($this->faker->boolean()) {
                $role = 'MANAGER';
            }

            $participation->setProject($this->getRandomReference(Project::class))
                ->setUser($this->getRandomReference(User::class))
                ->setRole($role);
        });
    }
}
