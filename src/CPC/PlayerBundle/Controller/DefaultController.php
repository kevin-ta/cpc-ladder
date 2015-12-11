<?php

namespace CPC\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CPC\PlayerBundle\Entity\Player;
use CPC\RankingBundle\Entity\Ranking;
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

                    return $this->render('CPCPlayerBundle:Default:index.html.twig', array(
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

        if($team == null)
        {
            return $this->redirectToRoute('cpc_team_homepage', array(
                'id' => $videogame->getId(),
                'videogame' => $videogame,
            ));
        }

    	$games = $em->getRepository('CPCGameBundle:Game')->findOrdered($team);
    	$ranking = $em->getRepository('CPCRankingBundle:Ranking')->findByTeam($team);

        return $this->render('CPCPlayerBundle:Default:index.html.twig', array(
        	'player' => $player,
        	'videogame' => $videogame,
        	'ranking' => $ranking,
        	'games' => $games
        ));
    }

    public function validAction($id, $game)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $player = $em->getRepository('CPCPlayerBundle:Player')->findOneBy(array(
    		'user' => $user->getId(),
    		'videogame' => $id
    	));
        $team = $player->getTeam();
    	$games = $em->getRepository('CPCGameBundle:Game')->findOrdered($team);
    	$ranking = $em->getRepository('CPCRankingBundle:Ranking')->findByTeam($team);
        $ranking1 = new Ranking();
        $ranking2 = new Ranking();
        $videogame = $em->getRepository('CPCVideoGameBundle:VideoGame')->findOneById($id);
        $game = $em->getRepository('CPCGameBundle:Game')->findOneById($game);
        $team2 = $em->getRepository('CPCPlayerBundle:Player')->findOneBy(array(
            'user' => $user->getId(),
            'videogame' => $id
        ))->getTeam();

    	if($game == null)
        {
            return $this->render('CPCPlayerBundle:Default:index.html.twig', array(
        		'player' => $player,
        		'games' => $games,
        		'ranking' => $ranking,
        		'videogame' => $videogame,
            	'error' => 'Ce match est introuvable.'
        	));
        }

        if($team2 != $game->getTeam2())
        {
            return $this->render('CPCPlayerBundle:Default:index.html.twig', array(
        		'player' => $player,
        		'games' => $games,
        		'ranking' => $ranking,
        		'videogame' => $videogame,
            	'error' => 'Vous ne pouvez pas valider ce match.'
        	));
        }

        if($game->getIsValid() == 1)
        {
            return $this->render('CPCPlayerBundle:Default:index.html.twig', array(
        		'player' => $player,
        		'games' => $games,
        		'ranking' => $ranking,
        		'videogame' => $videogame,
            	'error' => 'Ce match a déjà été validé.'
        	));
        }

        $teamA = $game->getTeam1();
        $teamB = $game->getTeam2();

        if(abs($teamB->getCurrentScore()-$teamA->getCurrentScore()) > 400)
        {
        	if($teamB->getCurrentScore() > $teamA->getCurrentScore())
        	{
        		$eloA = 1/(1+pow(10,1));
        		$eloB = 1/(1+pow(10,-1));
        	}
        	else
        	{
        		$eloA = 1/(1+pow(10,-1));
        		$eloB = 1/(1+pow(10,1));
        	}
        }
        else
        {
        	$eloA = 1/(1+pow(10,($teamB->getCurrentScore()-$teamA->getCurrentScore())/400));
        	$eloB = 1/(1+pow(10,($teamA->getCurrentScore()-$teamB->getCurrentScore())/400));
        }

        $ranking1->setGame($game);
        $ranking1->setTeam($teamA);
        $ranking2->setGame($game);
        $ranking2->setTeam($teamB);

        if($game->getWinningTeam() == 0)
        {
        	$neweloteam1 = $teamA->getCurrentScore() + 35*(1 - $eloA);
        	$neweloteam2 = $teamB->getCurrentScore() - 35*($eloB);
        }
        else
        {
            $neweloteam1 = $teamA->getCurrentScore() - 35*($eloA);
        	$neweloteam2 = $teamB->getCurrentScore() + 35*(1 - $eloB);
        }

        $ranking1->setScoreEvolution(round($neweloteam1, 0, PHP_ROUND_HALF_UP)-$teamA->getCurrentScore());
        $ranking2->setScoreEvolution(round($neweloteam2, 0, PHP_ROUND_HALF_UP)-$teamB->getCurrentScore());
        $teamA->setCurrentScore(round($neweloteam1, 0, PHP_ROUND_HALF_UP));
        $teamB->setCurrentScore(round($neweloteam2, 0, PHP_ROUND_HALF_UP));
        $game->setIsValid(1);

        $em->persist($ranking1);
        $em->persist($ranking2);
        $em->persist($teamA);
        $em->persist($teamB);
        $em->persist($game);
        $em->flush();

        return $this->redirectToRoute('cpc_player_homepage', array(
        	'id' => $videogame->getId(),
        	'player' => $player,
            'games' => $games,
        	'videogame' => $videogame,
        	'ranking' => $ranking
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $team = $player->getTeam();
    	$games = $em->getRepository('CPCGameBundle:Game')->findOrdered($team);
    	$ranking = $em->getRepository('CPCRankingBundle:Ranking')->findByTeam($team);
        $ranking1 = new Ranking();
        $ranking2 = new Ranking();
        $videogame = $em->getRepository('CPCVideoGameBundle:VideoGame')->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        $game = $em->getRepository('CPCGameBundle:Game')->findOneById($game);
        $team2 = $em->getRepository('CPCPlayerBundle:Player')->findOneBy(array(
            'user' => $user->getId(),
            'videogame' => $id
        ))->getTeam();

        if($team2 != $game->getTeam2())
        {
            return $this->render('CPCGameBundle:Default:index.html.twig', array(
        		'player' => $player,
        		'games' => $games,
        		'ranking' => $ranking,
        		'videogame' => $videogame,
            	'error' => 'Vous ne pouvez pas valider ce match.'
        	));
        }

        if($game->getIsValid() == 1)
        {
            return $this->render('CPCGameBundle:Default:index.html.twig', array(
        		'player' => $player,
        		'games' => $games,
        		'ranking' => $ranking,
        		'videogame' => $videogame,
            	'error' => 'Ce match a déjà été validé.'
        	));
        }

        return $this->render('CPCGameBundle:Default:index.html.twig', array(
            'team1' => $team1,
            'videogame' => $videogame,
            'form' => $form->createView(),
        ));
    }
}