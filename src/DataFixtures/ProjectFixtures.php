<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\Task;
use App\Entity\User;

use Faker\Generator;
use \App\Entity\Project;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProjectFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return[
            UserFixtures::class
        ];
    }

    public function loadData(ObjectManager $manager)
    {


        $this->createMany(Project::class, 10, function (Project $project) {





            $createdAt = $this->faker->dateTimeBetween('-6 month');

            $project->setTitle($this->faker->catchPhrase)
                ->setDescription($this->faker->markdown)
                ->setShortDescription($this->faker->paragraph())
                ->setCreatedAt($createdAt)
                ->setOwner($this->getRandomReference(User::class));

            if ($this->faker->boolean(66) === true) {

                $deadline = clone $createdAt;
                $days = mt_rand(15, 90);
                $deadline->modify("+$days days");
                $project->setDeadline($deadline);
            }
        });



        // $manager->flush();
    }
}
