<?php
/**
 * HeartbeatListener.php file
 *
 * File that contains the help wiki heartbeatf listener class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Listener/PageListener.php
 * @package    Mesd\HelpWikiBundle\Listener
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Listener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Mesd\HelpWikiBundle\Event\HeartbeatEvent;

/**
 * Heartbeat Listener
 *
 * Listens for events dispatched by the heartbeat and returns data as required.
 *
 * @package    Mesd\HelpWikiBundle\Listener
 * @copyright  2015 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.2.0
 */
class HeartbeatListener
{
    public function populateData(HeartbeatEvent $event)
    {
        //$event->setData(array('mesd-data' => 'user_is_logged_in'));
        
        return $event;
    }
}