<?php

namespace App\Tests\Project;

use App\Entity\User;
use App\Entity\Project;
use DateTime;
use Liior\SymfonyTestHelpers\WebTestCase;

class ProjectTest extends WebTestCase
{

    public function test_a_project_will_have_a_created()
    {

        $user = $this->createOne(User::class, function (User $user) {
            $user->setFullname('jojo')
                ->setEmail('ff@gmail.com')
                ->setPassword('osef');
        });

        $project = new Project;
        $project->setTitle('test')
            ->setShortDescription('bala')
            ->setDescription('test')
            ->setOwner($user);

        $manager = $this->getManager();
        $manager->persist($project);
        $this->assertInstanceOf(DateTime::class, $project->getCreatedAt());
    }
}
