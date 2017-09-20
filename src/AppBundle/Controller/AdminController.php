<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
    * @Route("/user", name="user_list")
    */
    public function listAction()
    {
        return $this->render('user/index.html.twig', array(
        ));
    }
}
