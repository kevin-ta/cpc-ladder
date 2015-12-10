<?php

namespace CPC\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CPC\PlayerBundle\Entity\Player;
use CPC\PlayerBundle\Form\PlayerType;

class DefaultController extends Controller
{
    public function profileAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$user = $this->get('security.context')->getToken()->getUser();
    	$videogame = $em->getRepository('CPCVideoGameBundle:VideoGame')->findOneById($id);
    	$player = $em->getRepository('CPCPlayerBundle:Player')->findOneBy(array(
    		'user' => $user->getId(),
    		'videogame' => $id
    	));

    	if($player == null)
    	{
    		$player = new Player();
        	$form = $this->createForm(new PlayerType($em), $player);

	    	if($request->isMethod('POST'))
	        {
	            $form->handleRequest($request);

	            if(! $form->isValid())
	            {
	                return $this->render('CPCGameBundle:Default:create.html.twig', array(
	                    'error' => $form->getErrorsAsString()
	                ));
	            }

	            $player->setUser($user);
	            $player->setVideoGame($videogame);
	            $em->persist($player);
	            $em->flush();

	            if($this->getRequest()->request->get('submit') == 'team')
	            {
	            	$player->setTeam(null);
	            	$em->persist($player);
					$em->flush();
					$team = $player->getTeam();
    				$games = $em->getRepository('CPCVideoGameBundle:VideoGame')->findByTeam1($team);
    				$ranking = $em->getRepository('CPCRankingBundle:Ranking')->findByTeam($team);

	            	return $this->render('CPCGameBundle:Default:index.html.twig', array(
	            		'player' => $player,
	            		'games' => $games,
	            		'ranking' => $ranking,
	            		'videogame' => $videogame,
	                	'success' => 'Ton profil a bien été créé, deviens maintenant le meilleur dresseur, et bats-toi sans répit.'
	            	));
	            }

	            return $this->render('CPCPlayerBundle:Default:index.html.twig', array(
        			'videogame' => $videogame,
        			'success' => 'Ton profil a bien été créé, deviens maintenant le meilleur dresseur, et bats-toi sans répit.'
       		 	));
	        }

	        return $this->render('CPCPlayerBundle:Default:create.html.twig', array(
	            'videogame' => $videogame,
	            'form' => $form->createView(),
	        ));
    	}

    	$team = $player->getTeam();
    	$games = $em->getRepository('CPCGameBundle:Game')->findOrdered($team);
    	$ranking = $em->getRepository('CPCRankingBundle:Ranking')->findByTeam($team);

        return $this->render('CPCPlayerBundle:Default:index.html.twig', array(
        	'player' => $player,
        	'videogame' => $videogame,
        	'ranking' => $ranking,
        	'games' => $games
        ));
    }
}