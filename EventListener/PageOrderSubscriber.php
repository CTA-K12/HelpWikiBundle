<?php

namespace Mesd\HelpWikiBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
// for Doctrine 2.4: Doctrine\Common\Persistence\Event\LifecycleEventArgs;

use Mesd\HelpWikiBundle\Model\PageOrder;
use Mesd\HelpWikiBundle\Entity\Page;

class PageOrderSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'preUpdate',
        );
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $en = $args->getEntity();
        $em = $args->getEntityManager();

        // perhaps you only want to act on some "Product" entity
        if ($en instanceof PageOrder)
        {
            // ... do something with the Product
        }
    }
}
