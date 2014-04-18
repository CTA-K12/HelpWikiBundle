<?php

namespace Mesd\HelpWikiBundle\Listener;

use Mesd\HelpWikiBundle\Entity\Page;
use Mesd\HelpWikiBundle\Entity\Comment;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Page
 */
class CommentListener
{

    private $page;

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        $sc = $this->container->get('security.context');

        if ($en instanceof Comment) {
            $en->setDateTime(new \DateTime());
            $en->setUser($sc->getToken()->getUser());
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
        //$em = $args->getManager();
        //$uow = $em->getUnitOfWork();
        //$sc = $this->container->get('security.context');

        if ($en instanceof Comment) {

            // look for changed values
            $body  = $args->hasChangedField('body')  ? $args->getOldValue('body')  : $en->getBody();

            // set the values to the comment
            $en->setBody($body);
            $en->setDateTime(new \DateTime());

            // recompute the entity changes
            //$md = $em->getClassMetadata('Mesd\HelpWikiBundle\Entity\Page');
            //$uow->recomputeSingleEntityChangeSet($md, $en);
        }
    }
}