<?php

namespace CPC\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CPCPlayerBundle:Default:index.html.twig');
    }
}
