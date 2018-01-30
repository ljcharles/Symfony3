<?php

namespace OC\PlatformBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommandeControllerTest extends WebTestCase
{
    public function testSeecommande()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/seeCommande');
    }

}
