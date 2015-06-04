<?php
/**
 * PageListener.php file
 *
 * File that contains the help wiki page listener class
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

use Mesd\HelpWikiBundle\Entity\Page;
use Mesd\HelpWikiBundle\Entity\History;
use Mesd\HelpWikiBundle\Entity\Link;

/**
 * Page Listener
 *
 * The help wiki page listener.
 * Updates the user, status, datetime, and revision when persist or update is
 * invoked. Also updates the history entity on update. On delete, however;
 * comments, history, tags, and page permissions are all permanently deleted
 * along with the original entity.
 *
 * @package    Mesd\HelpWikiBundle\Listener
 * @copyright  2015 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.2.0
 */
class PageListener
{

    private $history;

    private $link;

    private $container;

    private $routeAlias;

    public function __construct(ContainerInterface $container)
    {
        $this->container  = $container;
        //$this->routeAlias = $routeAlias;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        // set date time
        // set revision
        // set user
        // set print order
        // set is page locked
        // set is comment locked
        $en = $args->getEntity();
        $em = $args->getEntityManager();
        $sc = $this->container->get('security.context');

        if ($en instanceof Page) {
            $parent = $en->getParent();

            if ($en->isStandAlone() && !empty($parent))
            {
                return false;
            }

            $en->setDateTime(new \DateTime());
            $en->setRevision(0);
            $en->setUser($sc->getToken()->getUser());
            $en->setPageLocked(false);
            $en->setCommentsLocked(false);
            $en->setEditInProgress(false);

            // look for a last page, if there is one
            $lastPage = $em->getRepository('MesdHelpWikiBundle:Page')->findBy(
                array('parent' => $parent),
                array('printOrder' => 'DESC')
            );

            // check if there is a last page, if not, populate the order with 0
            $en->setPrintOrder((!empty($lastPage) ? ($lastPage[0]->getPrintOrder() + 1) : (0)));
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $en = $args->getEntity();
        $em = $args->getEntityManager();

        if ($en instanceof Page) {

            $this->routeAlias = $this->container->get('routealias');

            if ($this->routeAlias) {
                // create a new link based on page data
                $this->link = new Link();
                $this->link->setRouteAlias($this->routeAlias);
                $this->link->setPage($en);

                $em->persist($this->link);

                $em->flush();
            }
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        // set date time
        // set revision
        // set user
        // set print order
        // set is page locked
        // set is comment locked
        $en  = $args->getEntity();
        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $sc  = $this->container->get('security.context');

        if ($en instanceof Page) {

            $parent   = $en->getParent();
            $children = $en->getChildren();

            if ($en->isStandAlone() && (!empty($parent) || !$children->empty()))
            {
                return false;
            }

            // create new history based on old data
            $this->history = new History();

            // look for changed values
            $title  = $args->hasChangedField('title')  ? $args->getOldValue('title')  : $en->getTitle();
            $body   = $args->hasChangedField('body')   ? $args->getOldValue('body')   : $en->getBody();
            $slug   = $args->hasChangedField('slug')   ? $args->getOldValue('slug')   : $en->getSlug();
            $status = $args->hasChangedField('status') ? $args->getOldValue('status') : $en->getStatus();

            // set the values to the history
            $this->history->setTitle($title);
            $this->history->setBody($body);
            $this->history->setSlug($slug);
            $this->history->setStatus($status);

            // not modifiable by user
            $this->history->setPage($en);
            $this->history->setDateTime($en->getDateTime());
            $this->history->setRevision($en->getRevision());
            $this->history->setUser($en->getUser());

            // update the entity with new values
            $en->setDateTime(new \DateTime());
            $en->setRevision($en->getRevision() + 1);
            $en->setUser($sc->getToken()->getUser());

            // recompute the entity changes
            $md = $em->getClassMetadata('Mesd\HelpWikiBundle\Entity\Page');
            $uow->recomputeSingleEntityChangeSet($md, $en);
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();

        if ($this->history instanceof History) {
            $em->persist($this->history);

            $em->flush();
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $en = $args->getEntity();
        $em = $args->getEntityManager();

        // being that pages are core to the help wiki bundle,
        // almost all other objects in this bundle are associated with it.
        // it is imperitive to remove either the entities or their associations
        // prior to removing a page

        if ($en instanceof Page) {
            // be sure to delete all the links associated with the page
            $links = $em->getRepository('MesdHelpWikiBundle:Link')->findByPage($en);
            foreach ($links as $link) {
                $em->remove($link);
            }

            // delete all the tags associated with the page
            //$tags = $em->getRepository('MesdHelpWikiBundle:Tags')->findByPage($en);
            //foreach ($tags as $tag) {
            //    $em->remove($tag);
            //}

            // delete all the histories associated with the page
            $histories = $em->getRepository('MesdHelpWikiBundle:History')->findByPage($en);
            foreach ($histories as $history) {
                $em->remove($history);
            }

            // delete all the comments associated with the page
            $comments = $em->getRepository('MesdHelpWikiBundle:Comment')->findByPage($en);
            foreach ($comments as $comment) {
                $em->remove($comment);
            }

            // delete all the permissions associated with the page
            $permissions = $em->getRepository('MesdHelpWikiBundle:PagePermission')->findByPage($en);
            foreach ($permissions as $permission) {
                $em->remove($permission);
            }

            // delete only the parent association of all child pages
            $pages = $em->getRepository('MesdHelpWikiBundle:Page')->findByParent($en);
            foreach ($pages as $page) {
                $page->setParent(null);
                $em->persist($page);
            }
        }
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        // Matched route
        $_route  = $request->attributes->get('_route');

        // Matched controller
        $_controller = $request->attributes->get('_controller');

        if ('Mesd\HelpWikiBundle\Controller\PageController::editAction' === $_controller) {

            $em = $this->container->get('doctrine')->getManager();
            $en = $em->getRepository('MesdHelpWikiBundle:Page')->find(1);
        }
        // All route parameters including the `_controller`
        $params = $request->attributes->get('_route_params');

    }
}