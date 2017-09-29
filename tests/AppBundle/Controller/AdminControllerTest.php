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

        $values = $form->getPhpValues();

        $form['user[name]'] = 'Lucas';

        $crawler = $client->request($form->getMethod(), $form->getUri(), $values,
        $form->getPhpFiles());

        $this->assertTrue($crawler->filter('html:contains("Lucas")')->count() > 0);

    }
}