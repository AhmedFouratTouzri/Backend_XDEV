<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;

class CommandeController extends Controller
{
    /**
     * @Rest\Route("/order/add")
     */
    public function postAction(Request $request)
    {
        $data = new Commande();
        $status = $request->get('status');
        $name = $request->get('name');
        $lastname = $request->get('lastname');
        $adress = $request->get('adress');
        $state = $request->get('state');
        $zipcode = $request->get('zipcode');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $ordernotes = $request->get('ordernotes');


        //$data->setDatePublication($date_publication);
        //$data->setTitre($titre);
        $data->setStatus(0);
        $data->setName($name);
        $data->setLastname($lastname);
        $data->setAdress($adress);
        $data->setState($state);
        $data->setZipcode($zipcode);
        $data->setEmail($email);
        $data->setPhone($phone);
        $data->setOrdernotes($ordernotes);


        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new Response("order Added Successfully", Response::HTTP_OK);
    }
    /**
     * @Rest\Route("/order/add")
     */
    public function addDetailOrderAction(Request $request)
    {
        $data = new Commande();
        $status = $request->get('status');
        $name = $request->get('name');
        $lastname = $request->get('lastname');
        $adress = $request->get('adress');
        $state = $request->get('state');
        $zipcode = $request->get('zipcode');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $ordernotes = $request->get('ordernotes');


        //$data->setDatePublication($date_publication);
        //$data->setTitre($titre);
        $data->setStatus(0);
        $data->setName($name);
        $data->setLastname($lastname);
        $data->setAdress($adress);
        $data->setState($state);
        $data->setZipcode($zipcode);
        $data->setEmail($email);
        $data->setPhone($phone);
        $data->setOrdernotes($ordernotes);


        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new Response("order Added Successfully", Response::HTTP_OK);
    }
}
