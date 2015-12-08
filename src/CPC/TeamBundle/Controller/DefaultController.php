<?php

namespace CPC\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CPCTeamBundle:Default:index.html.twig');
    }
}
