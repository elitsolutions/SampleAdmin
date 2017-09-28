<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Users;
use AppBundle\Form\UserType;
use AppBundle\Entity\Groups;
use AppBundle\Form\GroupType;

class AdminController extends Controller
{
    /**
    * @Route("/user", name="user_list")
    */
    public function listAction()
    {
        // find all users
        $users = $this->getDoctrine()
        ->getRepository(Users::class)
        ->findAll();

        return $this->render('admin/index.html.twig', array(
            'users' => $users
        ));
    }

    /**
    * @Route("/user/add", name="user_add")
    */
    public function addAction(Request $request)
    {
        $user = new Users();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($formData);
            $em->flush();
    
            return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    * @Route("/user/{id}", name="show_user", requirements={"id": "\d+"})
    */
    public function showAction($id)
    {
        $user = $this->getDoctrine()
        ->getRepository(Users::class)
        ->find($id);

        return $this->render('admin/show.html.twig', array(
            'user' => $user
        ));
    }

    /**
    * @Route("/user/edit/{id}", name="edit_user", requirements={"id": "\d+"})
    */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Users::class)->find($id);
        $form = $this->createForm(UserType::class, $user);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $em->persist($formData);
            $em->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
    * @Route("/user/delete/{id}", name="delete_user", requirements={"id": "\d+"})
    */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository(Users::class)->find($id);
        $em->remove($user);
        $em->flush();
    
        return $this->redirectToRoute('user_list');
    }

    /**
    * @Route("/user/remove/{id}", name="remove_user_from_group", requirements={"id": "\d+"})
    */
    public function removeFromGroupAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Users::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $user->setGroup(null);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('user_list');

    }

    /**
    * @Route("/user/{user_id}/addToGroup/{group_id}", name="add_user_to_group", requirements={"user_id": "\d+", "group_id": "\d+"})
    */
    public function addUserToGroupAction($user_id, $group_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Users::class)->find($user_id);
        $group = $em->getRepository(Groups::class)->find($group_id);

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

        $user->setGroup($group_id);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('show_group', array('id' => $group_id));

    }
}
