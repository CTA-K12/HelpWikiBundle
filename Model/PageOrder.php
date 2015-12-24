<?php
/**
 * PageOrder.php file
 *
 * File that contains the help wiki page manager class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Model/PageManager.php
 * @package    Mesd\HelpWikiBundle\Model
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Page Order
 *
 * Manages page entities. Mostly calls functions that don't really belong in
 * either the entity class or repository.
 *
 * @package    Mesd\HelpWikiBundle\Model
 * @copyright  2015 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.2.0
 */
class PageOrder
{
    private $entityManager;

    private $repository;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $pages;

    /**
     * Constructor
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository    = $this->entityManager->getRepository('MesdHelpWikiBundle:Page');

        $this->pages = $this->repository->findAllByTree(array('flatten' => true));
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function addPage($page)
    {
        $this->pages[] = $page;

        return $this;
    }

    public function removePage($page)
    {
        $this->pages->removeElement($page);

        return $this;
    }

    public function hasPage($page)
    {
        return in_array($page, $this->pages);
    }

    public function flush($form)
    {
        //var_dump(get_class_methods($form));
        $pages = $form->getData()->getPages();

        var_dump($form->getData()->getPages());
        foreach ($pages as $page)
        {
            var_dump(array(
                'id'       => $page->getId(),
                'slug'     => $page->getSlug(),
                'position' => $page->getPosition(),
                'level'    => $page->getLevel(),
            ));
        }
        exit;
    }

    private function sortSubCollections($entities)
    {
        foreach ($entities as $entity)
        {
            if (!$entity->getChildren()->isEmpty())
            {
                $children = $this->sortCollectionByPosition($entity->getChildren());
                $this->sortSubCollections($children);

                $entity->setChildren($children);
            }
        }
    }

    private function sortCollectionByPosition(Collection $collection)
    {
        $iterator = $collection->getIterator();

        $iterator->uasort(function ($a, $b) {
            return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
        });

        $collection = new ArrayCollection(iterator_to_array($iterator));

        return $collection;
    }
}
