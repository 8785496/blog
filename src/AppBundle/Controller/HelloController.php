<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;

class HelloController extends Controller
{
    /**
     * @Route("/app/example", name="homepage")
     */
    public function indexAction($name = 10)
    {
        return $this->render('default/index.html.twig', [
            'phpinfo' => "Hello, $name"
        ]);
    }
    
    public function createAction() 
    {
        $product = new Product();
        $product->setName('A Foo Bar');

        $em = $this->getDoctrine()->getManager();

        $em->persist($product);
        $em->flush();

        return new Response('Created product id '.$product->getId());
    }
}
