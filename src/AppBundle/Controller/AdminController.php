<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
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
    public function listAction(Request $request)
    {
        $normalizers = new ObjectNormalizer();
        
        $normalizers->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });

        $encoders = new JsonEncoder();
        
        $serializer = new Serializer(array($normalizers), array($encoders));

        // get api argument value
        $api = $request->query->get('api');

        // find all users
        $users = $this->getDoctrine()
        ->getRepository(Users::class)
        ->findAll();

        // if $api is set and is true, show as json
        if(!is_null($api) && $api == 'true')
        {
            $response = new Response();
            $jsonContent = $serializer->serialize($users, 'json');
            $response->setContent($jsonContent);
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_OK);

            return $response;
        }
        else
        {
            return $this->render('admin/index.html.twig', array(
                'users' => $users
            ));
        }
    }

    /**
    * @Route("/user/add", name="user_add")
    */
    public function addAction(Request $request)
    {
        $normalizers = new ObjectNormalizer();
        
        // $normalizers->setCircularReferenceHandler(function ($object) {
        //     return $object->getName();
        // });

        $encoders = new JsonEncoder();
        
        $serializer = new Serializer(array($normalizers), array($encoders));

        // get api argument value
        $api = $request->query->get('api');

        $user = new Users();

        $form = $this->createForm(UserType::class, $user);

        // if $api is set and is true, post and return id
        if(!is_null($api) && $api == 'true')
        {
            $formData = json_decode($request->getContent(), true);
            
            // check if we have required name key value

            if(isset($formData['name']) && !empty($formData['name']))
            {                
                $em = $this->getDoctrine()->getManager();

                // check if group is set and if it is integer
                if(isset($formData['group']) && intval($formData['group']))
                {
                    $groupId = intval($formData['group']);
                    $groups = $em->getRepository(Groups::Class)->find($groupId);
                }
                else
                {
                    $groups = null;
                }
                
                $user->setName($formData['name']);
                $user->setGroup($groups);

                $em->persist($user);
                $em->flush();
        
                $response = new Response();
                $jsonContent = $serializer->serialize(array('status'=>'added'), 'json');
                $response->setContent($jsonContent);
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode(Response::HTTP_OK);
    
                return $response;
            }
            else
            {
                // form is not valid
                $response = new Response();
                $jsonContent = $serializer->serialize(array('status'=>'something is missing'), 'json');
                $response->setContent($jsonContent);
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
    
                return $response;
            }
        }
        else
        {   
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
    }

    /**
    * @Route("/user/{id}", name="show_user", requirements={"id": "\d+"})
    */
    public function showAction($id, Request $request)
    {
        $normalizers = new ObjectNormalizer();
        
        $normalizers->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });

        $encoders = new JsonEncoder();
        
        $serializer = new Serializer(array($normalizers), array($encoders));

        $user = $this->getDoctrine()
        ->getRepository(Users::class)
        ->find($id);

        // get api argument value
        $api = $request->query->get('api');

        // if $api is set and is true, show as json
        if(!is_null($api) && $api == 'true')
        {
            $response = new Response();
            $jsonContent = $serializer->serialize($user, 'json');
            $response->setContent($jsonContent);
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_OK);

            return $response;
        }
        else
        {
            return $this->render('admin/show.html.twig', array(
                'user' => $user
            ));
        }
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
        $em = $this->getDoctrine()->getManager();
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
}
