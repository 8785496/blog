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
    /**
     * @Route("/update/{id}", name="post_update")
     */
    public function updateAction($id, Request $request) 
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No post found for id '.$id
            );
        }
        
        $form = $this->createFormBuilder($post)
            ->add('authorId', 'hidden')    
            ->add('content', 'text')
            ->add('refresh', 'submit', array('label' => 'Refresh Post'))
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('post_id', ['id' => $post->getPostId()]);
        }

        return $this->render('post/create.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/", name="home")
     */
    public function postsAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->findAll();
        
        return $this->render('post/posts.html.twig', [
            'posts' => $posts
        ]);
    }
    /**
     * @Route("/delete/{id}", name="post_delete")
     */
    public function deleteAction($id) 
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->find($id);
        
        if (!$post) {
            throw $this->createNotFoundException(
                'No post found for id '.$id
            );
        }
        
        $em->remove($post);
        $em->flush();
        
        return $this->redirectToRoute('home');
    }
}

