<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
    * matches user
    * 
    * @Route("/user", name="user_list")
    */
    public function listAction()
    {
        echo 'index';
        // return $this->render('user/index.html.twig', array(
        // ));
    }
}
