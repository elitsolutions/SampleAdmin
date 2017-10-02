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
use AppBundle\Entity\Groups;
use AppBundle\Form\GroupType;
use AppBundle\Entity\Users;
use AppBundle\Form\UserType;

class GroupController extends Controller
{
    /**
    * @Route("/group", name="group_list")
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

        // find all groups
        $groups = $this->getDoctrine()->getRepository(Groups::class)->findAll();

        // if $api is set and is true, show as json
        if(!is_null($api) && $api == 'true')
        {
            $response = new Response();
            $jsonContent = $serializer->serialize($groups, 'json');
            $response->setContent($jsonContent);
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_OK);

            return $response;
        }
        else
        {
            return $this->render('group/index.html.twig', array(
                'groups' => $groups
            ));
        }
    }

    /**
    * @Route("/group/add", name="group_add")
    */
    public function addAction(Request $request)
    {
        $normalizers = new ObjectNormalizer();
        
        $encoders = new JsonEncoder();
        
        $serializer = new Serializer(array($normalizers), array($encoders));

        // get api argument value
        $api = $request->query->get('api');

        $group = new Groups();

        $form = $this->createForm(GroupType::class, $group);

        // if $api is set and is true, post and return id
        if(!is_null($api) && $api == 'true')
        {
            $formData = json_decode($request->getContent(), true);
            
            // check if we have required name key value
            if(isset($formData['name']) && !empty($formData['name']))
            {                
                $em = $this->getDoctrine()->getManager();
                
                $group->setName($formData['name']);

                $em->persist($group);
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
    }

    /**
    * @Route("/group/{id}", name="show_group", requirements={"id": "\d+"})
    */
    public function showAction($id, Request $request)
    {
        $normalizers = new ObjectNormalizer();
        
        $normalizers->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });

        $encoders = new JsonEncoder();
        
        $serializer = new Serializer(array($normalizers), array($encoders));

        $group = $this->getDoctrine()
        ->getRepository(Groups::class)
        ->find($id);

        // get api argument value
        $api = $request->query->get('api');
        
        // if $api is set and is true, show as json
        if(!is_null($api) && $api == 'true')
        {
            if (!$group) {
                // id is wrong
                $response = new Response();
                $jsonContent = $serializer->serialize(array('status'=>'No group found for id '.$id), 'json');
                $response->setContent($jsonContent);
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
    
                return $response;
            }

            $response = new Response();
            $jsonContent = $serializer->serialize($group, 'json');
            $response->setContent($jsonContent);
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_OK);

            return $response;
        }
        else
        {
            return $this->render('group/show.html.twig', array(
                'groups' => $group
            ));
        }
    }

    /**
    * @Route("/group/edit/{id}", name="edit_group", requirements={"id": "\d+"})
    */
    public function editAction($id, Request $request)
    {
        $normalizers = new ObjectNormalizer();
        
        $normalizers->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });

        $encoders = new JsonEncoder();
        
        $serializer = new Serializer(array($normalizers), array($encoders));

        $em = $this->getDoctrine()->getManager();
        $group = $em->getRepository(Groups::class)->find($id);

        // get api argument value
        $api = $request->query->get('api');
        
        // if $api is set and is true, show as json
        if(!is_null($api) && $api == 'true')
        {
            if (!$group) {
                // id is wrong
                $response = new Response();
                $jsonContent = $serializer->serialize(array('status'=>'No group found for id '.$id), 'json');
                $response->setContent($jsonContent);
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
    
                return $response;
            }

            $formData = json_decode($request->getContent(), true);

            // check if we have required name key value
            if(isset($formData['name']) && !empty($formData['name']))
            {                
                $em = $this->getDoctrine()->getManager();

                $group->setName($formData['name']);

                $em->persist($group);
                $em->flush();
        
                $response = new Response();
                $jsonContent = $serializer->serialize(array('status'=>'edited'), 'json');
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
            $form = $this->createForm(GroupType::class, $group);
            
            if (!$group) {
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
    }

    /**
    * @Route("/group/delete/{id}", name="delete_group", requirements={"id": "\d+"})
    */
    public function deleteAction($id, Request $request)
    {
        // only allow deleting via post request
        if ($request->isMethod('POST')) 
        {
            $normalizers = new ObjectNormalizer();
    
            $encoders = new JsonEncoder();
            
            $serializer = new Serializer(array($normalizers), array($encoders));

            $em = $this->getDoctrine()->getEntityManager();
            $group = $em->getRepository(Groups::class)->find($id);
            $users = $em->getRepository(Users::class)->findBy(['group'=>$group]);
            $usersInTheGroup = count($users);

            // get api argument value
            $api = $request->query->get('api');
            
            // if $api is set and is true, show as json
            if(!is_null($api) && $api == 'true')
            {
                $response = new Response();
                
                if (!$group) {
                    $message = 'No group found for id '.$id;
                    $response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
                }
                else
                {
                    if($usersInTheGroup != 0){
                        $message = 'You can not delete a group if it has users belonging to it!';
                        $response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
                    }
                    else
                    {
                        $em->remove($group);
                        $em->flush();
    
                        $message = 'Deleted';
                        $response->setStatusCode(Response::HTTP_OK);
                    }
                }

                $jsonContent = $serializer->serialize(array('status'=>$message), 'json');
                $response->setContent($jsonContent);
                $response->headers->set('Content-Type', 'application/json');
                
                return $response;
            }
            else
            {
                if (!$group) {
                    throw $this->createNotFoundException(
                        'No group found for id '.$group_id
                    );
                }
        
                if($usersInTheGroup != 0){
                    $this->addFlash(
                        'notice',
                        'You can not delete a group if it has users belonging to it!'
                    );
        
                    return $this->redirectToRoute('show_group', array('id' => $id));
                }
        
                $em->remove($group);
                $em->flush();
            
                return $this->redirectToRoute('group_list');
            }
        }
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
