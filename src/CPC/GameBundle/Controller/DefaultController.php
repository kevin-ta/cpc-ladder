<?php

namespace CPC\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CPC\GameBundle\Form\GameType;
use CPC\GameBundle\Entity\Game;
use CPC\RankingBundle\Entity\Ranking;
use CPC\TeamBundle\Entity\Team;

class DefaultController extends Controller
{
    public function indexAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager();
        $game = new Game();
        $ranking1 = new Ranking();
        $ranking2 = new Ranking();
        $videogame = $em->getRepository('CPCVideoGameBundle:VideoGame')->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        $team1 = $em->getRepository('CPCPlayerBundle:Player')->findOneBy(array(
            'user' => $user->getId(),
            'videogame' => $id
        ))->getTeam();

        $form = $this->createForm(new GameType($videogame, $team1->getName()), $game);

        if($request->isMethod('POST'))
        {            
            $form->handleRequest($request);

            if(! $form->isValid())
            {
                return $this->render('CPCGameBundle:Default:index.html.twig', array(
                    'error' => $form->getErrorsAsString(),
                    'videogame' => $videogame,
                    'team1' => $team1,
                    'form' => $form->createView(),
                ));
            }

            if($request->request->get('0') != null) $game->setWinningTeam(0);
            else $game->setWinningTeam(1);

            date_default_timezone_set('Europe/Paris');
            $game->setDate(new \DateTime("now"));
            $game->setTeam1($team1);

            $team1 = $game->getTeam1();
            $team2 = $game->getTeam2();

            $ranking1->setGame($game);
            $ranking1->setTeam($team1);
            $ranking2->setGame($game);
            $ranking2->setTeam($team2);
            if($game->getWinningTeam() === 0)
            {
                $ranking1->setScoreEvolution(2);
                $ranking2->setScoreEvolution(-2);
            }
            else
            {
                $ranking1->setScoreEvolution(-2);
                $ranking2->setScoreEvolution(2);
            }

            $team1->setCurrentScore($team1->getCurrentScore() + $ranking1->getScoreEvolution());
            $team2->setCurrentScore($team2->getCurrentScore() + $ranking2->getScoreEvolution());

            $em->persist($game);
            $em->persist($ranking1);
            $em->persist($ranking2);
            $em->persist($team1);
            $em->persist($team2);
            $em->flush();

            return $this->render('CPCGameBundle:Default:index.html.twig', array(
                    'videogame' => $videogame,
                    'team1' => $team1,
                    'form' => $form->createView(),
                    'success' => 'Le match a bien été rentré, allez consulter votre classement.'
                ));
        }

        return $this->render('CPCGameBundle:Default:index.html.twig', array(
            'team1' => $team1,
            'videogame' => $videogame,
            'form' => $form->createView(),
        ));
    }
}
