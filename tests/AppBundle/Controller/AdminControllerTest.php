<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    // public function testIndex()
    // {
    //     $client = static::createClient();

    //     $crawler = $client->request('GET', '/user');

    //     $this->assertEquals(200, $client->getResponse()->getStatusCode());
    //     $this->assertContains('Welcome to Sample Admin page!', $crawler->filter('.container h1')->text());
    // }

    // public function testAddUser()
    // {
    //     $client = static::createClient();

    //     $crawler = $client->request('GET', '/user/add');

    //     $form = $crawler->selectButton('Add User')->form();

    //     $form['user[name]'] = 'Tim';
    //     $form['user[group]'] = '1';

    //     $client->submit($form);

    //     $this->assertEquals('AppBundle\Controller\AdminController::addAction', $client->getRequest()->attributes->get('_controller'));
    // }

    // public function testShowUser()
    // {
    //     $client = static::createClient();

    //     $crawler = $client->request('GET', '/user');

    //     $link = $crawler->filter('a:contains("Elmar")')->link();
        
    //     $crawler = $client->click($link);

    //     $this->assertContains(
    //         'Elmar',
    //         $client->getResponse()->getContent()
    //     );
    // }

    // public function testEditUser()
    // {
    //     $client = static::createClient();

    //     $crawler = $client->request('GET', '/user/edit/6');

    //     $form = $crawler->selectButton('Add User')->form();

    //     $form['user[name]'] = 'Elmar I';
    //     $form['user[group]'] = '2';

    //     $client->submit($form);

    //     $this->assertEquals('AppBundle\Controller\AdminController::editAction', $client->getRequest()->attributes->get('_controller'));

    // }

    // public function testDeleteUser()
    // {
    //     $client = static::createClient();

    //     $crawler = $client->request('GET', '/user/delete/24');

    //     $this->assertEquals('AppBundle\Controller\AdminController::deleteAction', $client->getRequest()->attributes->get('_controller'));

    // }
}