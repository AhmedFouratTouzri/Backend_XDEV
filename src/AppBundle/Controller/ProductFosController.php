<?php

namespace AppBundle\Controller;

use Composer\Repository\RepositoryInterface;
use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

class ProductFosController extends FOSRestController
{
    /**
     * @Rest\Post("/product/add")
     */
    public function postAction(Request $request)
    {

        $product = new Product();
        $items = json_decode($request->getContent(), true);


        //$date_publication = $request->get('date_publication');
        $refProd = $items["ref_prod"];
        // var_dump($refProd);
        $nomProd = $items["nom_prod"];
        $prix = $items["prix"];
        $qteStock = $items["qte_stock"];
        $image = $items["image"];
        $info = $items["info"];
        $nbLike = $items["nb_like"];

        //$data->setDatePublication($date_publication);
        $product->setRefProd($refProd);
        $product->setNomProd($nomProd);
        $product->setPrix($prix);
        $product->setQteStock($qteStock);
        $product->setImage($image);
        $product->setInfo($info);
        $product->setNbLike($nbLike);
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $category_id = $data['category_id'];
        $cat = $em->getRepository('AppBundle:Category')->find($category_id);
        $product->setCategory($cat);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response("Product Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/product")
     */
    public function getAction()
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository('AppBundle:Product')->findAll();
        $data = $this->get('jms_serializer')->serialize($prod, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application\json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Rest\Get("/product/{id}")
     */
    public function idAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository('AppBundle:Product')->find($id);
        $data = $this->get('jms_serializer')->serialize($prod, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }


    /**
     * @Rest\Delete("/removeProduct/{id}")
     */
    public
    function deleteAction($id)
    {
        $data = new Product();
        $em = $this->getDoctrine()->getManager();
        $prod = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        if (empty($prod)) {
            return new Response("Product not found", Response::HTTP_NOT_FOUND);
        } else {
            $em->remove($prod);
            $em->flush();
        }
        return new Response("deleted successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/updateProduct/{id}")
     */
    public function updateAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        if (empty($product)) {
            return new Response("Product not found", Response::HTTP_NOT_FOUND);
        }
        $data=$request->getContent();
        $array=json_decode($data,true);
        $refProd = $array["ref_prod"];
        $nomProd = $array["nom_prod"];
        $prix = $array["prix"];
        $qteStock = $array["qte_stock"];
        $image = $array["image"];
        $info = $array["info"];
        $nbLike = $array["nb_like"];

        $product->setRefProd($refProd);
        $product->setNomProd($nomProd);
        $product->setPrix($prix);
        $product->setQteStock($qteStock);
        $product->setImage($image);
        $product->setInfo($info);
        $product->setNbLike($nbLike);
        $em->persist($product);
        $em->flush();

        return new Response("Product Updated Successfully", Response::HTTP_OK);
    }



    /**
     * @Rest\Get("/productCategory/{id}")
     */
    public function productbycategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository('AppBundle:Product')->getProductById($id);
        $data = $this->get('jms_serializer')->serialize($prod, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}
