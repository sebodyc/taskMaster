<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\Task;
use Faker\Generator;

use \App\Entity\Project;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class ProjectFixtures extends Fixture
{
    protected Generator $faker;
    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create('fr_FR');
        $this->faker->addProvider(new \DavidBadura\FakerMarkdownGenerator\FakerProvider($this->faker));


        for ($p = 0; $p < 5; $p++) {
            $project = new Project;
            $createdAt = $this->faker->dateTimeBetween('-6 month');

            $project->setTitle($this->faker->catchPhrase)
                ->setDescription($this->faker->markdown)
                ->setShortDescription($this->faker->paragraph())
                ->setCreatedAt($createdAt);

            if ($this->faker->boolean(66) === true) {

                $deadline = clone $createdAt;
                $days = mt_rand(15, 90);
                $deadline->modify("+$days days");
                $project->setDeadline($deadline);
            }
            $manager->persist($project);

            for ($t = 0; $t < mt_rand(2, 6); $t++) {
                $task = new Task;
                $task->setTitle($this->faker->catchPhrase)
                    ->setDescription($this->faker->markdownP())
                    ->setCompleted($this->faker->boolean())
                    ->setProject($project);

                $createdAt = clone $createdAt;
                $createdAt->modify('+' . mt_rand(0, 3) . 'days');

                $task->setCreatedAt($createdAt);

                if ($this->faker->boolean(40)) {
                    $deadline = clone $createdAt;
                    $days = mt_rand(3, 5);
                    $deadline->modify("+$days days");
                    $task->setDeadline($deadline);
                }

                $manager->persist($task);
            }
        }

        $manager->flush();
    }
}
