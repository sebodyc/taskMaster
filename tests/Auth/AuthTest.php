<?php

namespace App\Tests\Auth;

use Liior\SymfonyTestHelpers\Exception\ClientNotCreatedException;
use Liior\SymfonyTestHelpers\WebTestCase;
use LogicException;
use RuntimeException;
use PHPUnit\Framework\ExpectationFailedException;

class AuthTest extends WebTestCase
{
    public function test_webapp_is_closed_if_user_is_not_authenticated()
    {
        $this->get('/');

        $code = $this->client->getResponse()->getStatusCode();

        $this->assertGreaterThanOrEqual(300, $code);
    }

    public function test_webapp_is_accessible_is_user_is_authenticated()
    {
        $this->authenticate($this->client, "Joseph D'Arimatie");

        $this->get("/");

        $this->assertResponseIsSuccessful();
    }
}
