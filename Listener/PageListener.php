<?php

namespace Mesd\HelpWikiBundle\Listener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Mesd\HelpWikiBundle\Entity\Page;
use Mesd\HelpWikiBundle\Entity\History;
use Mesd\HelpWikiBundle\Entity\Link;

/**
 * Page
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
        $em = $args->getEntityManager(); // this should be getManager, not getEntityManager
        $sc = $this->container->get('security.context');

        if ($en instanceof Page) {
            $parent = $en->getParent();

            $en->setDateTime(new \DateTime());
            $en->setRevision(0);
            $en->setUser($sc->getToken()->getUser());
            $en->setIsPageLocked(false);
            $en->setIsCommentLocked(false);
            $en->setIsEditInProgress(false);

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
        $em = $args->getEntityManager();
        $en = $args->getEntity();

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
        $en = $args->getEntity();
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $sc = $this->container->get('security.context');

        if ($en instanceof Page) {

            // create new history based on old data
            $this->history = new History();

            // look for changed values
            $title = $args->hasChangedField('title') ? $args->getOldValue('title') : $en->getTitle();
            $body  = $args->hasChangedField('body')  ? $args->getOldValue('body')  : $en->getBody();
            $slug  = $args->hasChangedField('slug')  ? $args->getOldValue('slug')  : $en->getSlug();

            // set the values to the history
            $this->history->setTitle($title);
            $this->history->setBody($body);
            $this->history->setSlug($slug);

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
            $permissions = $em->getRepository('MesdHelpWikiBundle:Permissions')->findByPage($en);
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
        $params      = $request->attributes->get('_route_params');

    }
}