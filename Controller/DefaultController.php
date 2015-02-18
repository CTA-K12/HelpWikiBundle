<?php
/**
 * DefaultController.php file
 *
 * File that contains the default controller
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

class DefaultController extends Controller
{

    /**
     * Index action
     *
     * @return \Twig $this
     */
    public function indexAction()
    {
        return $this->render('MesdHelpWikiBundle:Default:index.html.twig', array());
    }
}
