<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Constraints\Date;

class CommentController extends FOSRestController
{
    /**
     * @Rest\Get("/comments")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Commentaire')->findAll();
        if ($restresult === null) {
            return new View("there are no comments exist", Response::HTTP_NOT_FOUND);
        }
        return new Response($restresult);
    }

    /**
     * @Rest\Get("/comment/{id}")
     */
    public function idAction($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Commentaire')->find($id);
        if ($singleresult === null) {
            return new View("comment not found", Response::HTTP_NOT_FOUND);
        }
        return new Response($singleresult);
    }

    /**
     * @Rest\Post("/comment/add")
     */
    public function postAction(Request $request)
    {
        $data = new Commentaire();
        //$date_publication = $request->get('date_publication');
        $contenu = $request->get('contenu');



        //$data->setDatePublication($date_publication);
        $data->setContenu($contenu);
        $em = $this->getDoctrine()->getManager();
        $parameters = json_decode($request->getContent(), true);
        $parameters2 = json_decode($request->getContent(), true);
        $user_id=$parameters['user_id'];
        $user = $em->getRepository('AppBundle:User')->find($user_id);
        $data->setUser($user);
        $id_post=$parameters2['post_id'];
        $post = $em->getRepository('AppBundle:Post')->find($id_post);
        $data->setPost($post);
        $em->persist($data);
        $em->flush();
        return new Response("post Added Successfully", Response::HTTP_OK);
    }






    /**
     * @Rest\Put("/commentupdate/{id}")
     */
    public function updateAction($id,Request $request)
    {
        $comment = new Commentaire();
        $contenu = $request->get('contenu');

        $em = $this->getDoctrine()->getManager();
        $commentaire = $this->getDoctrine()->getRepository('AppBundle:Commentaire')->find($id);
        if (empty($commentaire)) {
            return new Response("post not found", Response::HTTP_NOT_FOUND);
        }

        $comment->setContenu($contenu);
        $em->flush();

        return new Response("comment Updated Successfully", Response::HTTP_OK);
    }


    /**
     * @Rest\Delete("/removecomment/{id}")
     */
    public function deleteAction($id)
    {
        $data = new Commentaire();
        $em = $this->getDoctrine()->getManager();
        $comment = $this->getDoctrine()->getRepository('AppBundle:Commentaire')->find($id);
        if (empty($comment)) {
            return new Response("post not found", Response::HTTP_NOT_FOUND);
        }
        else {
            $em->remove($comment);
            $em->flush();
        }
        return new Response("deleted successfully", Response::HTTP_OK);
    }



    /**
     * @Rest\Put("/comment/{id}")
     */
    public function blockAction($id,Request $request)
    {
        $data = new Commentaire();
        $contenu = $request->get('contenu');
        $datepublication = $request->get(new \DateTime());
        $sn = $this->getDoctrine()->getManager();
        $comment = $this->getDoctrine()->getRepository('AppBundle:Commentaire')->find($id);
        if (empty($comment)) {
            return new Response("user not found", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($name) && !empty($role)){
            $comment->setName($name);
            $comment->setRole($role);
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
