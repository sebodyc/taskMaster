<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;
use App\Entity\Project;
use App\DataFixtures\ProjectFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use NewException;

class TaskFixtures extends AbstractFixture implements DependentFixtureInterface
{



    public function getDependencies()
    {
        return [
            ProjectFixtures::class

        ];
    }





    public function loadData(ObjectManager $manager)
    {

        $this->createMany(Task::class, mt_rand(10, 30), function (Task $task) {

            $project = $this->getRandomReference(Project::class);
            $task->setTitle($this->faker->catchPhrase)
                ->setDescription($this->faker->markdownP())
                ->setCompleted($this->faker->boolean())
                ->setProject($project);


            $createdAt = clone $project->getCreatedAt();
            $createdAt->modify('+' . mt_rand(0, 3) . 'days');

            $task->setCreatedAt($createdAt);

            if ($this->faker->boolean(40)) {
                $deadline = clone $createdAt;
                $days = mt_rand(3, 5);
                $deadline->modify("+$days days");
                $task->setDeadline($deadline);
            }
        });


        // $manager->flush();
    }
}
