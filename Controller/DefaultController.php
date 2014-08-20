<?php
/**
 * /tmp/phptidy-sublime-buffer.php
 *
 * @author Morgan Estes <morgan.estes@gmail.com>
 * @package default
 */


namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    /**
     *
     *
     * @return unknown
     */
    public function indexAction()
    {
        return $this->render('MesdHelpWikiBundle:Default:index.html.twig', array());
    }
}
