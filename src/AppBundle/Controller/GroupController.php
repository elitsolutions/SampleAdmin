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
use AppBundle\Entity\Users;
use AppBundle\Form\UserType;

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
    public function addAction(Request $request)
    {
        $group = new Groups();

        $form = $this->createForm(GroupType::class, $group);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
    
            return $this->redirectToRoute('group_list');
        }

        return $this->render('group/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    * @Route("/group/{id}", name="show_group", requirements={"id": "\d+"})
    */
    public function showAction($id)
    {
        $groups = $this->getDoctrine()
        ->getRepository(Groups::class)
        ->find($id);

        return $this->render('group/show.html.twig', array(
            'groups' => $groups
        ));
    }

    /**
    * @Route("/group/edit/{id}", name="edit_group", requirements={"id": "\d+"})
    */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Groups::class)->find($id);
        $form = $this->createForm(GroupType::class, $user);

        if (!$user) {
            throw $this->createNotFoundException(
                'No group found for id '.$id
            );
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $em->persist($formData);
            $em->flush();

            return $this->redirectToRoute('group_list');
        }

        return $this->render('group/form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
    * @Route("/group/delete/{id}", name="delete_group", requirements={"id": "\d+"})
    */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $group = $em->getRepository(Groups::class)->find($id);
        $users = $em->getRepository(Users::class)->findBy(['group'=>$group]);

        if (!$group) {
            throw $this->createNotFoundException(
                'No group found for id '.$group_id
            );
        }

        $usersInTheGroup = count($users);
        if($usersInTheGroup == 0){
            throw $this->createNotFoundException(
                'You can not delete a group if it has users belonging to it'
            );
        }

        $em->remove($group);
        $em->flush();
    
        return $this->redirectToRoute('group_list');
    }

    /**
    * @Route("/group/addUser/{group_id}", name="add_user_to_group_show", requirements={"group_id": "\d+"})
    */
    public function addUserToGroupShowAction($group_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(Users::class)->findBy(array('group' => null));
        $group = $em->getRepository(Groups::class)->find($group_id);

        if (!$group) {
            throw $this->createNotFoundException(
                'No group found for id '.$group_id
            );
        }

        return $this->render('group/showUsers.html.twig', array(
            'users' => $users,
            'group' => $group
        ));

    }

    /**
    * @Route("/group/{group_id}/user/{user_id}", name="add_user_to_group", requirements={"group_id": "\d+", "user_id": "\d+"})
    */
    public function AddUserToGroupAction($group_id, $user_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $group = $em->getRepository(Groups::class)->find($group_id);
        $user = $em->getRepository(Users::class)->find($user_id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$user_id
            );
        }

        if (!$group) {
            throw $this->createNotFoundException(
                'No group found for id '.$group_id
            );
        }

        $user->setGroup($group);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('show_group', array('id' => $group_id));

    }
}
