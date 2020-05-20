<?php

namespace App\Tests\Auth;

use Liior\SymfonyTestHelpers\WebTestCase;

class LoginTest extends WebTestCase
{

    public function test_user_can_see_login_form()
    {

        $page = $this->get('/login');



        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[name="_username"]');
        $this->assertSelectorExists('input[name="_password"]');
    }
}
