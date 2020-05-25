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
        $this->authenticate($this->client, 'robert');
        // 2..en appliquant tel action

        $this->get('/');

        // 3..je devrais avoir


        $this->assertResponseStatusCodeSame(200);

        $this->assertSee("Projets");
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


        $this->authenticate($this->client, $user);


        $this->get('/projects/' . $project->getId());
        //test
        $this->assertResponseStatusCodeSame(200);
        $this->assertSee($project->getTitle());
    }
}
