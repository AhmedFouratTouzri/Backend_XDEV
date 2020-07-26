<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
class DefaultController extends FOSRestController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Rest\Get("/last_listings")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Emplacement')->getLastAddedListings();
        if ($restresult === null) {
            return new Response("there are no posts exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Route("/signup")
     */
    public function postAction(Request $request)
    {
        $data = new User();
        $name = $request->get('name');
        $lastname = $request->get('lastname');
        $adress = $request->get('adress');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $passwd = $request->get('password');
        $password = password_hash($passwd, PASSWORD_BCRYPT);
        $username = $request->get('username');
//        $role = "admin";
//        $status = "enabled";
//        $category = "abc";


        //$data->setDatePublication($date_publication);
        //$data->setTitre($titre);
        $data->setStatus(1);
        $data->setName($name);
        $data->setLastname($lastname);
        $data->setAdresse($adress);
        $data->setEmail($email);
        $data->setPassword($password);
        $data->setUsername($username);
        $data->setPhone($phone);
        $data->setRole('ROLE_USER');
        $data->setCategory("abc"); ///


        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new Response("User Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Route("/my-account/getAll")
     * @return Response
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        $data = $this->get('jms_serializer')->serialize($users, 'json');
        $response = new Response($data);
        return $response;
    }

    /**
     * @Route("/my-account/get/{id}")
     * @param User $user
     * @return Response
     */
    public function getUserByIdAction(User $user){
        $data=$this->get('jms_serializer')->serialize($user,'json');
        $response=new Response($data);
        return $response;
    }

    /**
     * @Route("/my-account/update/{id}")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function updateUserAction(Request $request, $id){
        $em=$this->getDoctrine()->getManager();
        $user= $em->getRepository(User::class)->find($id);
        $data=$request->getContent();
        $newUser=$this->get('jms_serializer')->deserialize($data,User::class,'json');
        $user->setName($newUser->getName());
        $user->setLastname($newUser->getLastname());
        $user->setUsername($newUser->getUsername());
        $user->setEmail($newUser->getEmail());
        $user->setAdresse($newUser->getAdresse());
        $pwd = $newUser->getPassword();
        $password = password_hash($pwd, PASSWORD_BCRYPT);
        $user->setPassword($password);
        $em->persist($user);
        $em->flush();
        return new Response('User updated successfully',201);
    }


    /**
     * @Route("/my-account/delete/{id}")
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteUserAction(Request $request){
        $id = $request->get('id');
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository(User::class)->find($id);
        $em->remove($user);
        $em->flush();
        return new JsonResponse(['msg'=>'User deleted with success!'],200);
    }

}
