<?php

namespace CPC\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CPC\PlayerBundle\Form\PlayerType;
use CPC\TeamBundle\Form\TeamType;
use CPC\TeamBundle\Form\TeamExistType;
use CPC\PlayerBundle\Entity\Player;
use CPC\TeamBundle\Entity\Team;
use CPC\RankingBundle\Entity\Ranking;

class DefaultController extends Controller
{
	public function indexAction($id, $teamid)
    {
    	$em = $this->getDoctrine()->getManager();
    	$user = $this->get('security.context')->getToken()->getUser();
    	$videogame = $em->getRepository('CPCVideoGameBundle:VideoGame')->findOneById($id);
    	$player = $em->getRepository('CPCPlayerBundle:Player')->findOneBy(array(
    		'user' => $user->getId(),
    		'videogame' => $id
    	));

        if($teamid != null)
        {
            $team = $em->getRepository('CPCTeamBundle:Team')->findOneById($teamid);
            $games = $em->getRepository('CPCGameBundle:Game')->findOrdered($team);
            $ranking = $em->getRepository('CPCRankingBundle:Ranking')->findByTeam($team);

            return $this->render('CPCTeamBundle:Default:index.html.twig', array(
                'team' => $team,
                'videogame' => $videogame,
                'ranking' => $ranking,
                'games' => $games
            ));
        }

    	if($player == null)
    	{
	        return $this->redirectToRoute('cpc_team_createplayer', array(
                'id' => $videogame->getId(),
            ));
    	}

    	$team = $player->getTeam();

        if($team == null)
        {
            return $this->redirectToRoute('cpc_team_createteam', array(
                'id' => $videogame->getId(),
            ));
		}

    	$games = $em->getRepository('CPCGameBundle:Game')->findOrdered($team);
    	$ranking = $em->getRepository('CPCRankingBundle:Ranking')->findByTeam($team);

        return $this->render('CPCTeamBundle:Default:index.html.twig', array(
        	'player' => $player,
        	'videogame' => $videogame,
        	'ranking' => $ranking,
        	'games' => $games,
        ));
    }

    public function playerAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $videogame = $em->getRepository('CPCVideoGameBundle:VideoGame')->findOneById($id);
        $player = $em->getRepository('CPCPlayerBundle:Player')->findOneBy(array(
            'user' => $user->getId(),
            'videogame' => $id
        ));

        if($player != null)
        {
            return $this->redirectToRoute('cpc_team_homepage', array(
                'id' => $videogame->getId()
            ));
        }

        $player = new Player();
        $form = $this->createForm(new PlayerType($videogame), $player);

        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);

            if(! $form->isValid())
            {
                return $this->redirectToRoute('cpc_team_homepage', array(
                    'id' => $videogame->getId()
                ));
            }

            $player->setUser($user);
            $player->setVideoGame($videogame);

            if($this->getRequest()->request->get('submit') == 'team')
            {
                $player->setTeam(null);
                $em->persist($player);
                $em->flush();
            }

            $em->persist($player);
            $em->flush();

            return $this->redirectToRoute('cpc_team_homepage', array(
                'id' => $videogame->getId()
            ));
        }

        return $this->render('CPCTeamBundle:Default:create_player.html.twig', array(
            'videogame' => $videogame,
            'form' => $form->createView(),
        ));
    }

    public function teamAction(Request $request, $id)
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
            return $this->redirectToRoute('cpc_team_homepage', array(
                'id' => $videogame->getId()
            ));
        }

        $team = $player->getTeam();

        if($team != null)
        {
            return $this->redirectToRoute('cpc_team_homepage', array(
                'id' => $videogame->getId()
            ));
        }

        $teams = $em->getRepository('CPCTeamBundle:Team')->findAll();

        if($request->isMethod('POST'))
        {
            if($this->getRequest()->request->get('submit') == 'new')
            {
                $team = new Team();
                $team->setVideoGame($videogame);
                $team->setCurrentscore(1200);
                $team->setName($this->getRequest()->request->get('new'));
                $em->persist($team);
                $player->setTeam($team);
            }
            elseif($this->getRequest()->request->get('submit') == 'exist')
            {
                $team = $em->getRepository('CPCTeamBundle:Team')->findOneById($this->getRequest()->request->get('team'));
                $player->setTeam($team);
            }

            $em->persist($player);
            $em->flush();

            $games = $em->getRepository('CPCGameBundle:Game')->findOrdered($team);
            $ranking = $em->getRepository('CPCRankingBundle:Ranking')->findByTeam($team);

            return $this->redirectToRoute('cpc_team_homepage', array(
                'id' => $videogame->getId()
            ));
        }

        return $this->render('CPCTeamBundle:Default:create_team.html.twig', array(
            'videogame' => $videogame,
            'teams' => $teams
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
            return $this->render('CPCTeamBundle:Default:index.html.twig', array(
        		'player' => $player,
        		'games' => $games,
        		'ranking' => $ranking,
        		'videogame' => $videogame,
            	'error' => 'Ce match est introuvable.'
        	));
        }

        if($team2 != $game->getTeam2())
        {
            return $this->render('CPCTeamBundle:Default:index.html.twig', array(
        		'player' => $player,
        		'games' => $games,
        		'ranking' => $ranking,
        		'videogame' => $videogame,
            	'error' => 'Vous ne pouvez pas valider ce match.'
        	));
        }

        if($game->getIsValid() == 1)
        {
            return $this->render('CPCTeamBundle:Default:index.html.twig', array(
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

        return $this->redirectToRoute('cpc_team_homepage', array(
            'id' => $videogame->getId()
        ));
    }

    public function deleteAction($id, $game)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $player = $em->getRepository('CPCPlayerBundle:Player')->findOneBy(array(
            'user' => $user->getId(),
            'videogame' => $id
        ));
        $team = $player->getTeam();
        $games = $em->getRepository('CPCGameBundle:Game')->findOrdered($team);
        $videogame = $em->getRepository('CPCVideoGameBundle:VideoGame')->findOneById($id);
        $game = $em->getRepository('CPCGameBundle:Game')->findOneById($game);
        $team2 = $em->getRepository('CPCPlayerBundle:Player')->findOneBy(array(
            'user' => $user->getId(),
            'videogame' => $id
        ))->getTeam();

        if($game == null)
        {
            return $this->render('CPCTeamBundle:Default:index.html.twig', array(
                'player' => $player,
                'games' => $games,
                'ranking' => $ranking,
                'videogame' => $videogame,
                'error' => 'Ce match est introuvable.'
            ));
        }

        if($team2 != $game->getTeam2())
        {
            return $this->render('CPCTeamBundle:Default:index.html.twig', array(
                'player' => $player,
                'games' => $games,
                'ranking' => $ranking,
                'videogame' => $videogame,
                'error' => 'Vous ne pouvez pas supprimer ce match.'
            ));
        }

        if($game->getIsValid() == 1)
        {
            return $this->render('CPCTeamBundle:Default:index.html.twig', array(
                'player' => $player,
                'games' => $games,
                'ranking' => $ranking,
                'videogame' => $videogame,
                'error' => 'Ce match a été validé, il ne peut pas être supprimé.'
            ));
        }

        $em->remove($game);
        $em->flush();

        return $this->redirectToRoute('cpc_team_homepage', array(
            'id' => $videogame->getId()
        ));
    }
}
