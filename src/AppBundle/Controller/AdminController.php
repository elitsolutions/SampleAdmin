<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Users;

class AdminController extends Controller
{
    /**
    * @Route("/user", name="user_list")
    */
    public function listAction()
    {
        // find all users
        $users = $this->getDoctrine()->getRepository(Users::class)->findAll();

        return $this->render('admin/index.html.twig', array(
            'users' => $users
        ));
    }

    /**
    * @Route("/user/add", name="user_add")
    */
    public function addAction()
    {
        $user = new Users();

        // $form = $this->createFormBuilder($user)
        // ->add('name', TextType::class)
        // ->add('save', SubmitType::class, array('label' => 'Add User'))
        // ->getForm();

        return $this->render('admin/form.html.twig', array(
            // 'form' => $form->createView(),
        ));
    }
}
