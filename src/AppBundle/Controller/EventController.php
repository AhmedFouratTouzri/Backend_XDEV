<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Event;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\FOSRestBundle;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
/**
 * Event controller.
 *
 * @Route("event")
 */
class EventController extends FOSRestController
{
    /**
     * @Rest\Post("/add")
     */
    public function postAction(Request $request)
{
    $event = new Event();
    //$date_publication = $request->get('date_publication');
    $titre = $request->get('titre');
    $description = $request->get('description');
    $datedebut = $request->get('datedebut');
    $prix = $request->get('prix');
    $image = $request->get('image');

    $event->setTitre($titre);
    $event->setDescription($description);
    $event->setDatedebut($datedebut);
    $event->setPrix($prix);
    $event->setImage($image);
    $em = $this->getDoctrine()->getManager();
    $parameters = json_decode($request->getContent(), true);
    $user_id = $parameters['iduser'];

    $user = $em->getRepository('AppBundle:User')->find($user_id);
    $event->setIduser($user);
    $em=$this->getDoctrine()->getManager();
    $em->persist($event);
    $em->flush();
    return new jsonResponse("post Added Successfull");


}

    /**
     * @Rest\Put("/eventupdate/{id}")
     */
    public function updateAction($id,Request $request)
{
    $event = new Event();
    $titre = $request->get('titre');
    $description = $request->get('description');
    $datedebut = $request->get('datedebut');
    $prix = $request->get('prix');
    $image = $request->get('image');

    $em = $this->getDoctrine()->getManager();
    $event = $this->getDoctrine()->getRepository('AppBundle:Event')->find($id);
    if (empty($event)) {
        return new Response("event not found", Response::HTTP_NOT_FOUND);
    }

    $event->setTitre($titre);
    $event->setDescription($description);
    $event->setDatedebut($datedebut);
    $event->setPrix($prix);
    $event->setImage($image);
    $em->flush();

    return new Response("Post Updated Successfully", Response::HTTP_OK);
}


    /**
     * @Rest\Get("/events")
     */
    public function getAction()
{
    $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findAll();
    if ($events === null) {
        return new View("liste evenements vide", Response::HTTP_NOT_FOUND);
    }
    $data = $this->get('jms_serializer')->serialize($events, 'json');
    $response = new Response($data);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
}

    /**
     * @Rest\Delete("/deleteevenement/{id}")
     */
    public function deleteAction(Request $request)
{
    $id = $request->get('id');

    $em = $this->getDoctrine()->getManager();
    $events = $em->getRepository('AppBundle:Event')->find($id);
    $em->remove($events);
    $em->flush();

    return new JsonResponse("event successfully deleted", 200);
}
    /**
     * @Rest\get("/getEventbyid/{id}")
     */
    public function showEmpbyidddAction(Event $event)
{
    $data = $this->get('jms_serializer')->serialize($event , 'json');
    $response = new Response($data);

    return $response;
}

}
