<?php
/**
 * LinkManager.php file
 *
 * File that contains the help wiki link manager class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Model/LinkManager.php
 * @package    Mesd\HelpWikiBundle\Model
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\HelpWikiBundle\Model;

use Doctrine\ORM\EntityManager;
use Mesd\HelpWikiBundle\Entity\Link;

/**
 * Link Manager
 *
 * Manages link entities. Mostly calls functions that don't really belong in
 * either the entity class or repository.
 *
 * @package    Mesd\HelpWikiBundle\Model
 * @copyright  2015 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.2.0
 */
class LinkManager
{
    private $entityManager;

    private $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository    = $this->entityManager->getRepository('MesdHelpWikiBundle:Link');
    }

    public function isLinked($routeAlias)
    {
        $link = $this->repository->findOneByRouteAlias(strtolower($routeAlias));

        if ($link) {
            return $link;
        }

        $entity = new Link();
        return $entity;
    }
}