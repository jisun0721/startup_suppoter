<?php

namespace Support\FrontBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReplyControllerTest extends WebTestCase
{
    public function testAddreply()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/addReply');
    }

    public function testDeletereply()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/deleteReply');
    }

    public function testReplyform()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/replyForm');
    }

}
