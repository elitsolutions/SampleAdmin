<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Welcome to Sample Admin page!', $crawler->filter('.container h1')->text());
    }

    public function testAddUser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user/add');

        $form = $crawler->selectButton('Add User')->form();

        $form['user[name]'] = 'Timothy';

        $client->submit($form);

        $this->assertEquals('AppBundle\Controller\AdminController::addAction', $client->getRequest()->attributes->get('_controller'));


    }

    public function testShowUser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user');

        $link = $crawler->filter('a:contains("Elmar")')->link();
        
        $crawler = $client->click($link);

        $this->assertContains(
            'Elmar',
            $client->getResponse()->getContent()
        );
    }

    // public function testEditUser()
    // {
    //     $client = static::createClient();

    //     $crawler = $client->request('GET', '/user/edit/6');

    //     $form = $crawler->selectButton('Add User')->form();

    //     $values = $form->getPhpValues();

    //     $form['user[name]'] = 'Elmar I';

    //     $crawler = $client->request($form->getMethod(), $form->getUri(), $values,
    //     $form->getPhpFiles());

    //     $this->assertTrue($crawler->filter('html:contains("Elmar I")')->count() > 0);

    // }
}