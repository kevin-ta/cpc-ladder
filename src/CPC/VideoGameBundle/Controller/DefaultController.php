<?php

namespace CPC\VideoGameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function homepageAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$videogames = $em->getRepository('CPCVideoGameBundle:VideoGame')->findAll();
        return $this->render('CPCVideoGameBundle:Default:homepage.html.twig', array(
        	'videogames' => $videogames
        ));
    }

    public function indexAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$videogame = $em->getRepository('CPCVideoGameBundle:VideoGame')->findOneById($id);
        return $this->render('CPCVideoGameBundle:Default:index.html.twig', array(
        	'videogame' => $videogame
        ));
    }
}