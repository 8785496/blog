<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Post;

class PostController extends Controller
{
    /**
     * @Route("/post/{id}", name="post_id")
     */
    public function postAction($id)
    {
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No post found for id '.$id
            );
        }
        
        return $this->render('post/post.html.twig', [
            'post' => $post
        ]);
    }
    /**
     * @Route("/create", name="post_create")
     */
    public function createAction(Request $request) 
    {
        $post = new Post();
        $post->setAuthorId(1);

        $form = $this->createFormBuilder($post)
            ->add('authorId', 'hidden')    
            ->add('content', 'text')
            ->add('save', 'submit', array('label' => 'Create Post'))
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('post_id', ['id' => $post->getPostId()]);
        }

        return $this->render('post/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}

