<?php

namespace Support\FrontBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
    public function testSearchkeyword()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/searchKeyword');
    }

}
