<?php

namespace CPC\RankingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function classementAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$ranking = $em->getRepository('CPCTeamBundle:Team')->findOrdered($id);
    	$game = $em->getRepository('CPCVideoGameBundle:VideoGame')->findOneById($id);

        return $this->render('CPCRankingBundle:Default:index.html.twig', array(
        	'game' => $game,
        	'ranking' => $ranking
        ));
    }
}
