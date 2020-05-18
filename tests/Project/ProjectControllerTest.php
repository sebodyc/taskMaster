<?php

namespace App\Tests\Project;

use Liior\SymfonyTestHelpers\WebTestCase;



class ProjectControllerTest extends WebTestCase
{
    public function testUserCanSeeProject()
    {

        // 2..en appliquant tel action

        $this->get('/projects');

        // 3..je devrais avoir

        // $code = $client->getResponse()->getStatusCode();
        // $this->assertEquals(200, $code);

        // $this->assertResponseIsSuccessful();

        $this->assertResponseStatusCodeSame(200);

        $this->assertSee("Mes projets");
    }

    public function testUserCanAccesOneProject()
    {

        $this->get('/projects/6');
        $this->assertResponseStatusCodeSame(200);
        $this->assertSee("Le droit de concrétiser vos projets autrement");
    }
}
