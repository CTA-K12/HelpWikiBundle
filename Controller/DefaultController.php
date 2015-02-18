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
 * @version    0.1.0
 * @deprecated This file is not used and will be removed in future versions
 */
namespace Mesd\HelpWikiBundle\Controller;

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
}
