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
use Mesd\HelpWikiBundle\Entity\History;
use Mesd\HelpWikiBundle\Entity\Page;

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
    private $em;
    private $container;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em        = $em;
        $this->container = $container;
    }
    /*
    public function populateData(HeartbeatEvent $event)
    {
        $hb = $event->getHeartbeat();
        $data = $hb->getData()['page'];

        //$event->setData(array('mesd-data' => 'user_is_logged_in'));
        //$event->setData(array('mesd-user-data' => 'curtis is great'));
        
        $hb->setData($data);
        return $event;
    }
    */

    public function autoSave(HeartbeatEvent $event)
    {
        $heartbeat = $event->getHeartbeat();
        $pageId    = $heartbeat->getData()['page']['id'];
        $pageBody  = $heartbeat->getData()['page']['body'];

        $page      = $this->em->getRepository('MesdHelpWikiBundle:Page')->findOneById($pageId);

        $sc  = $this->container->get('security.context');

        if ($page instanceof Page)
        {
            //$event->getHeartbeat()->setData('i get to here');
            if ($page->getBody() != $pageBody)
            {
                $history = $this->em->getRepository('MesdHelpWikiBundle:History')->findOneBy(array('page' => $page, 'status' => 'AUTOSAVE'));

                if (!$history)
                {
                    $history = new History();
                }

                // set the values to the history
                $history->setTitle($page->getTitle());
                $history->setBody($pageBody);
                $history->setSlug($page->getSlug());
                $history->setStatus('AUTOSAVE');
                $history->setPage($page);
                $history->setDateTime(new \DateTime());
                $history->setRevision($page->getRevision());
                $history->setUser($sc->getToken()->getUser());

                //$this->em->persist($history);
                //$this->em->flush();
            }
        }
        
        return $event;
    }
}