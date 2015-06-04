<?php
/**
 * CommentListener.php file
 *
 * File that contains the comment listener class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Listener/CommentListener.php
 * @package    Mesd\HelpWikiBundle\Listener
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Listener;

use Mesd\HelpWikiBundle\Entity\Page;
use Mesd\HelpWikiBundle\Entity\Comment;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Comment Listener
 *
 * The help wiki comment listener.
 * Updates user, datetime, and status when any update or persist is invoked.
 *
 * @package    Mesd\HelpWikiBundle\Listener
 * @copyright  2015 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.2.0
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
            $en->setStatus($en::UNAPPROVED);
            $en->setStatusDateTime(new \DateTime());
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        // set body
        // set status by
        // set status date time
        $en = $args->getEntity();
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $sc = $this->container->get('security.context');

        if ($en instanceof Comment) {

            // look for changed values
            $body = $args->hasChangedField('body') ? $args->getOldValue('body') : $en->getBody();

            // set the values to the comment
            $en->setBody($body);
            $en->setStatusBy($sc->getToken()->getUser());
            $en->setStatusDateTime(new \DateTime());

            // recompute the entity changes
            $md = $em->getClassMetadata('Mesd\HelpWikiBundle\Entity\Comment');
            $uow->recomputeSingleEntityChangeSet($md, $en);
        }
    }
}