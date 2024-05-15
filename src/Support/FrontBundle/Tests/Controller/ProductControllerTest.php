<?php

namespace Support\FrontBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testDetail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/detail');
    }

}
