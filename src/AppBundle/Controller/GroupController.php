<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Groups;
use AppBundle\Form\GroupType;

class GroupController extends Controller
{
    /**
    * @Route("/group", name="group_list")
    */
    public function listAction()
    {
        // find all users
        $groups = $this->getDoctrine()->getRepository(Groups::class)->findAll();

        return $this->render('group/index.html.twig', array(
            'groups' => $groups
        ));
    }

    /**
    * @Route("/group/add", name="group_add")
    */
    public function addAction()
    {
        $group = new Groups();

        $form = $this->createForm(GroupType::class, $group);

        // $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();
    
            return $this->redirectToRoute('group_list');
        }

        return $this->render('group/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
