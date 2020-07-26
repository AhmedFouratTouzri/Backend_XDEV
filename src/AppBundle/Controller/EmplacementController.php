<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Schema\View;
use AppBundle\Entity\Emplacement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Emplacement controller.
 *
 * @Route("emplacement")
 */
class EmplacementController extends Controller
{
    /**
     * Lists all emplacement entities.
     *
     * @Route("/lists", name="emplacement_index")
     * @Method("GET")
     */
    public function getEmplacementAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Emplacement')->findAll();
        if ($restresult === null) {
            return new View("there are no Emplacement exist", Response::HTTP_NOT_FOUND);
        }


        $data = $this->get('jms_serializer')->serialize($restresult, 'json');
        $response = new Response($data);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * Creates a new emplacement entity.
     *
     * @Route("/add", name="emplacement_new")
     * @Method({"GET", "POST"})
     */
    public function addEMAction(Request $request)
    {

        $data=$request->getContent();


        $arrayemp= json_decode($request->getContent(), true);
        $categorie = $arrayemp["categorie"];
        $description = $arrayemp["description"];
        $adresse = $arrayemp["adresse"];
        $photo = $arrayemp["photo"];
        $nbr= $arrayemp["nbr"];
        $name = $arrayemp["name"];

        $emp=new Emplacement();
        $emp->setName($name);
        $emp->setDescription($description);
        $emp->setAdresse($adresse);
        $emp->setCategorie($categorie);
        $emp->setNbr($nbr);
        $emp->setPhoto($photo);


        $em = $this->getDoctrine()->getManager();
        $em->persist($emp);
        $em->flush();
        return new Response("reservation Added Successfully", 201);


    }

    /**
     * Finds and displays a emplacement entity.
     *
     * @Route("/emp/{id}", name="emplacement_show")
     * @Method("GET")
     * @param Emplacement $emplacement
     * @return Response
     */
    public function showEmpbyidddAction(Emplacement $emplacement)
    {
        $data = $this->get('jms_serializer')->serialize($emplacement , 'json');
        $response = new Response($data);

        return $response;
    }

    /**
     * Displays a form to edit an existing emplacement entity.
     *
     * @Route("/edit/{id}", name="emplacement_edit")
     * @Method({"PUt", "POST"})
     */
    public function updateAction(Request $request, $id)
    {

        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();
        $empl = $doctrine->getRepository('AppBundle:Emplacement')
            ->find($id);

        $data=$request->getContent();
        $arrayemp= json_decode($data, true);

        $description = $arrayemp["description"];
        $adresse = $arrayemp["adresse"];
        $photo = $arrayemp["photo"];
        $nbr= $arrayemp["nbr"];
        $name = $arrayemp["name"];


        $empl->setAdresse($adresse);
        $empl->setDescription($description);
        $empl->setName($name);
        $empl->setNbr($nbr);
        $empl->setPhoto($photo);

        $manager->persist($empl);
        $manager->flush();

        return new JsonResponse(['msg' => 'update succes!'], 200);
    }



    /**
     * Deletes a emplacement entity.
     *
     * @Route("/delete/{id}", name="emplacement_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request,$id)
    {
        $id = $request->get('id');
        //var_dump($id);
        $em = $this->getDoctrine()->getManager();
        $emplacement = $em->getRepository('AppBundle:Emplacement')->find($id);
        $em->remove($emplacement);
        $em->flush();

        return new JsonResponse(['msg' => 'deleted with succes!'], 200);
    }



}