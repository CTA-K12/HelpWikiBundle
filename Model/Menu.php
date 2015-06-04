<?php
/**
 * Menu.php file
 *
 * File that contains the help wiki menu model class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Model/Menu.php
 * @package    Mesd\HelpWikiBundle\Model
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Model;

/**
 * Menu
 *
 * While the menu itself is not a modifiable object,
 * it exists as an object so the permission system can apply ACE
 * to users accessing it.
 *
 * @package    Mesd\HelpWikiBundle\Model
 * @copyright  2015 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.2.0
 */
final class Menu
{
    const MENU = 'MENU';

    /**
     * @return string
     */
    public function getMenu()
    {
        return self::MENU;
    }
}