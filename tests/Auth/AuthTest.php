<?php

namespace App\Tests\Auth;

use Liior\SymfonyTestHelpers\WebTestCase;

class AuthTest extends WebTestCase
{
    /**
     * 
     *
     * @dataProvider urlProvider
     */
    public function test_webapp_is_closed_if_user_is_not_autenticated($url)
    {

        $this->get($url);

        $code = $this->client->getResponse()->getStatusCode();

        $this->assertGreaterThanOrEqual(300, $code);
    }
    

    public function test_webapp_is_accesible_if_user_is_autenticated()
    {
        $this->authenticate($this->client, "robert");

        $this->get("/projects");

        $this->assertResponseIsSuccessful();
    }


    public function urlProvider()
    {

        return [
            ['/'],
            ['/projects'],
            ['/robert'],
        ];
    }
}
