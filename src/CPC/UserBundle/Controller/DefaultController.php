<?php

namespace CPC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CPCUserBundle:Default:index.html.twig');
    }
}
