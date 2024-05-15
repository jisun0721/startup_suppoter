<?php

namespace Support\FrontBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyPageControllerTest extends WebTestCase
{
    public function testChangemyinfo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/change');
    }

}
