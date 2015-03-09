<?php
/**
 * RoleSubjectInterface.php file
 *
 * File that contains the help wiki role subject interface class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Model/RoleSubjectInterface.php
 * @package    Mesd\HelpWikiBundle\Model
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\HelpWikiBundle\Model;

/**
 * Role Subject Interface
 *
 * The interface the role subject should implement.
 * This links the application's role interface with help wiki bundle.
 * See the installation documentation for more information on it's use.
 *
 * @package    Mesd\HelpWikiBundle\Model
 * @copyright  2015 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.2.0
 */
interface RoleSubjectInterface
{
    // List any additional methods that your InvoiceBundle
    // will need to access on the subject so that you can
    // be sure that you have access to those methods.

    /**
     * @return string
     */
    public function getRole();
}