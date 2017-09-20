<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
    * matches lucky
    * 
    * @Route("/user")
    */
    public function indexAction()
    {
        return $this->render('user/index.html.twig', array(
        ));
    }
}
