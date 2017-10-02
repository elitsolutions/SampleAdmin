<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GroupControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/group');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddUser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/group/add');

        $form = $crawler->selectButton('Save Group')->form();

        $form['group[name]'] = 'Developers';

        $client->submit($form);

        $this->assertEquals('AppBundle\Controller\GroupController::addAction', $client->getRequest()->attributes->get('_controller'));
    }

    public function testShowGroup()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/group');

        $link = $crawler->filter('a')->last()->link();

        var_dump($link);
        
        // $crawler = $client->click($link);

        // $this->assertContains(
        //     'Elmar',
        //     $client->getResponse()->getContent()
        // );
    }

}