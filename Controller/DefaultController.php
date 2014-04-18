<?php

namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MesdHelpWikiBundle:Default:index.html.twig', array('subtitle' => 'Help Wiki Home',));
    }
}
