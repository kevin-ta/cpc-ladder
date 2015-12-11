<?php

namespace CPC\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CPC\PlayerBundle\Form\PlayerType;
use CPC\TeamBundle\Form\TeamType;
use CPC\PlayerBundle\Entity\Player;
use CPC\TeamBundle\Entity\Team;

class DefaultController extends Controller
{
    public function indexAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$videogame = $em->getRepository('CPCVideoGameBundle:VideoGame')->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
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
    	$form = $this->createForm(new TeamType($em), $team);

    	if($team == null)
    	{
    		$team = new Team();

			if($request->isMethod('POST'))
		    {
		        $form->handleRequest($request);

		        if(! $form->isValid())
		        {
		            return $this->render('CPCGameBundle:Default:index.html.twig', array(
		                'error' => $form->getErrorsAsString()
		            ));
		        }

		        $team->setCurrentscore(1200);
		        $team->setVideoGame($videogame);
		        $player->setTeam($team);
		        $em->persist($team);
		        $em->flush();

		        return $this->render('CPCTeamBundle:Default:index.html.twig', array(
		        	'videogame' => $videogame,
		        	'success' => 'Ton équipe a bien été créée.',
		        	'form' => $form->createView(),
		        ));
		    }
		}

        return $this->render('CPCTeamBundle:Default:index.html.twig', array(
        	'videogame' => $videogame,
        	'form' => $form->createView(),
        	'team' => $team
        ));
    }

    public function createAction($id)
    {

    }
}
