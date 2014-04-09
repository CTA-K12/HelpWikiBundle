<?php

namespace MESD\HelpWikiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MESDHelpWikiBundle:Default:index.html.twig', array('subtitle' => 'Help Wiki Home',));
    }
}
