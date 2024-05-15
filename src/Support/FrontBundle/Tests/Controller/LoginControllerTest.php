<?php

namespace Support\FrontBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testLoginemail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
    }

}
