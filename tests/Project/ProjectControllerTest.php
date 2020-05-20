<?php

namespace App\Tests\Project;

use App\Entity\Project;
use App\Entity\User;
use DateTime;
use Faker\Provider\DateTime as ProviderDateTime;
use Liior\SymfonyTestHelpers\WebTestCase;



class ProjectControllerTest extends WebTestCase
{
    public function testUserCanSeeProject()
    {

        // 2..en appliquant tel action

        $this->get('/projects');

        // 3..je devrais avoir


        $this->assertResponseStatusCodeSame(200);

        $this->assertSee("Mes projets");
    }

    public function testUserCanAccesOneProject()
    {
        $user = new User;
        $user->setEmail("onsenfou2@gmail.com")
            ->setPassword("password")
            ->setFullname("toto");

        $this->getManager()->persist($user);

        $project = new Project;
        $project->setTitle("titre2");
        $project->setShortDescription("petite description");
        $project->setDescription("longue description");
        $project->setowner($user);
        $project->setCreatedAt(new DateTime());

        $this->getManager()->persist($project);
        $this->getManager()->flush();




        $this->get('/projects/' . $project->getId());
        $this->assertResponseStatusCodeSame(200);
        $this->assertSee($project->getTitle());
    }
}
