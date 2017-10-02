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

        $link = $crawler->filter('a.show_group')->last()->link();
        $text = $crawler->filter('a.show_group')->last()->text();
        
        $crawler = $client->click($link);

        $this->assertContains(
            $text,
            $client->getResponse()->getContent()
        );
    }

    public function testEditGroup()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/group');
        
        $link = $crawler->filter('a.edit_group')->last()->attr('href');
        $text = $crawler->filter('a.show_group')->last()->text();

        $crawler = $client->request('GET', $link);

        $form = $crawler->selectButton('Save Group')->form();

        $form['group[name]'] = $text.' edited';

        $client->submit($form);

        $this->assertEquals('AppBundle\Controller\GroupController::editAction', $client->getRequest()->attributes->get('_controller'));

    }

    public function testDeleteGroup()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/group');

        $lastDeleteFormAction = $crawler->filter('form')->last()->attr('action');
        
        $client->request(
            'POST',
            $lastDeleteFormAction,
            array(),
            array(),
            array()
        );

        $this->assertEquals('AppBundle\Controller\AdminController::deleteAction', $client->getRequest()->attributes->get('_controller'));
    }
}