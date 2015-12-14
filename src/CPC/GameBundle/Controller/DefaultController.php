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
        $videogame = $em->getRepository('CPCVideoGameBundle:VideoGame')->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        $team = $em->getRepository('CPCPlayerBundle:Player')->findOneBy(array(
            'user' => $user->getId(),
            'videogame' => $id
        ));
        if($team == null)
        {
            return $this->redirectToRoute('cpc_team_createteam', array(
                'id' => $videogame->getId()
            ));
        }
        $team1 = $team->getTeam();

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

            $game->setDate(new \DateTime("now"));
            $game->setTeam1($team1);
            $game->setIsValid(0);

            $em->persist($game);
            $em->flush();

            return $this->render('CPCGameBundle:Default:index.html.twig', array(
                'videogame' => $videogame,
                'team1' => $team1,
                'form' => $form->createView(),
                'success' => 'Le match a bien été rentré, attendez que votre adversaire le valide.'
            ));
        }

        return $this->render('CPCGameBundle:Default:index.html.twig', array(
            'team1' => $team1,
            'videogame' => $videogame,
            'form' => $form->createView(),
        ));
    }
}
