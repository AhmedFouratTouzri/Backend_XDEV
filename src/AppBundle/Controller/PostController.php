<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class PostController extends FOSRestController
{
    /**
     * @Rest\Get("/posts")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Post')->findAll();
        if ($restresult === null) {
            return new Response("there are no posts exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/post/{id}")
     */
    public function idAction($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
        if ($singleresult === null) {
            return new View("post not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/post/add")
     */
    public function postAction(Request $request)
    {
        $data = new Post();
        //$date_publication = $request->get('date_publication');
        $titre = $request->get('titre');
        $description = $request->get('description');
        $contenu = $request->get('contenu');
        $image = $request->get('image');




        //$data->setDatePublication($date_publication);
        $data->setTitre($titre);
        $data->setDescription($description);
        $data->setContenu($contenu);
        $data->setImage($image);


        $em = $this->getDoctrine()->getManager();
        $parameters = json_decode($request->getContent(), true);
        $user_id=$parameters['user_id'];
        $user = $em->getRepository('AppBundle:User')->find($user_id);
        $data->setUser($user);
        $em->persist($data);
        $em->flush();
        return new Response("post Added Successfully", Response::HTTP_OK);
    }


    /**
     * @Rest\Get("/last_posts")
     */
    public function lastpostsAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Post')->getLastAddedPosts();
        if ($restresult === null) {
            return new Response("there are no posts exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }




    /**
     * @Rest\Put("/postupdate/{id}")
     */
    public function updateAction($id,Request $request)
    {
        $post = new Post();
        $titre = $request->get('titre');
        $description = $request->get('description');
        $contenu = $request->get('contenu');
        $image = $request->get('image');
        $em = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
        if (empty($post)) {
            return new View("post not found", Response::HTTP_NOT_FOUND);
        }

        $post->setTitre($titre);
        $post->setDescription($description);
        $post->setContenu($contenu);
        $post->setImage($image);
        $em->flush();

        return new View("Post Updated Successfully", Response::HTTP_OK);
    }


    /**
     * @Rest\Delete("/removepost/{id}")
     */
    public function deleteAction($id)
    {
        $data = new Post();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
        if (empty($user)) {
            return new View("post not found", Response::HTTP_NOT_FOUND);
        }
        else {
            $em->remove($user);
            $em->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }



    /**
     * @Rest\Put("/user/{id}")
     */
    public function blockAction($id,Request $request)
    {
        $data = new Post();
        $name = $request->get('name');
        $role = $request->get('role');
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($name) && !empty($role)){
            $user->setName($name);
            $user->setRole($role);
            $sn->flush();
            return new View("User Updated Successfully", Response::HTTP_OK);
        }
        elseif(empty($name) && !empty($role)){
            $user->setRole($role);
            $sn->flush();
            return new View("role Updated Successfully", Response::HTTP_OK);
        }
        elseif(!empty($name) && empty($role)){
            $user->setName($name);
            $sn->flush();
            return new View("User Name Updated Successfully", Response::HTTP_OK);
        }
        else return new View("User name or role cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }
}
