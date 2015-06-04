<?php
/**
 * DefaultController.php file
 *
 * File that contains the default controller class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Controller/DefaultController.php
 * @package    Mesd\HelpWikiBundle\Controller
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 * @deprecated This file is not used and will be removed in future versions
 */
namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Default Controller
 *
 * This controller directs the default route to an index page
 *
 * @package    Mesd\HelpWikiBundle\Controller
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.1.0
 * @todo       Not even sure this file is used. Possibly needs to be removed.
 */
class DefaultController extends Controller
{

    /**
     * Index Action
     *
     * Renders the index template from a specified route
     *
     * @author    Curtis G Hanson <chanson@mesd.k12.or.us>
     * @copyright 2014 MESD
     * @version   0.1
     * @return    Symfony\Component\HttpFoundation\Response $content The index twig response
     */
    public function indexAction()
    {
        return $this->render('MesdHelpWikiBundle:Default:index.html.twig', array());
    }

    public function heartbeatAction(Request $request)
    {
        /*
        $data = $request->request->all();

        $response = new JsonResponse();

        return $response->setData(array($data));
        */

        $data  = $request->request->all();
        $pulse = $this->get('mesd_help_wiki.heartbeat');
        $hb    = $pulse->onHeartbeatReceivedAction($data);

        $response = new JsonResponse();

        return $response->setData(array($hb));
    }
}
