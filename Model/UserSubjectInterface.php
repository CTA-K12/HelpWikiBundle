<?php
/**
 * UserSubjectInterface.php file
 *
 * File that contains the help wiki user subject interface class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Model/UserSubjectInterface.php
 * @package    Mesd\HelpWikiBundle\Model
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Model;

/**
 * User Subject Interface
 *
 * The interface the user subject should implement.
 * This links the application's user interface with help wiki bundle.
 * See the installation documentation for more information on it's use.
 *
 * @package    Mesd\HelpWikiBundle\Model
 * @copyright  2015 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.2.0
 */
interface UserSubjectInterface
{
    /**
     * Get id
     *
     * Return the user id
     * 
     * @return integer
     */
    public function getId();

    /**
     * Get Username
     *
     * Return the username
     * 
     * @return string
     */
    public function getUsername();
}