<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

class CategorieController extends FOSRestController
{
    /**
     * @Rest\Post("/categorie/add")
     */
    public function postAction(Request $request)
    {
        $data = new Category();
        //$date_publication = $request->get('date_publication');
        $name = $request->get('name');
        $description = $request->get('description');
        $image = $request->get('image');


        var_dump($data);

        //$data->setDatePublication($date_publication);
        $data->setName($name);
        $data->setDescription($description);
        $data->setImage($image);


        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new Response("Categorie Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/categories")
     */
    public function getAction()
    {
        $em = $this->getDoctrine()->getManager();
        $cat=$em->getRepository('AppBundle:Category')->findAll();
        $data=$this->get('jms_serializer')->serialize($cat,'json');
        $response=new Response($data);
        return $response;
    }

    /**
     * @Rest\Get("/categories/{id}")
     */
    public function idAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $cat = $em->getRepository('AppBundle:Category')->find($id);
        $data = $this->get('jms_serializer')->serialize($cat, 'json');
        $response = new Response($data);
        return $response;
    }

    /**
     * @Rest\Delete("/removecategories/{id}")
     */
    public function deleteAction($id)
    {
        $data = new Category();
        $em = $this->getDoctrine()->getManager();
        $cat = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        if (empty($cat)) {
            return new Response("category not found", Response::HTTP_NOT_FOUND);
        }
        else {
            $em->remove($cat);
            $em->flush();
        }
        return new Response("deleted successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/catupdate/{id}")
     */
    public function updateAction($id,Request $request)
    {
        $post = new Category();
        $name = $request->get('name');
        $description = $request->get('description');
        $image = $request->get('image');
        $em = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        if (empty($post)) {
            return new Response("category not found", Response::HTTP_NOT_FOUND);
        }

        $post->setName($name);
        $post->setDescription($description);
        $post->setImage($image);
        $em->flush();

        return new Response("Category Updated Successfully", Response::HTTP_OK);
    }
}
