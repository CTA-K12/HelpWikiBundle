<?php
/**
 * Permission.php file
 *
 * File that contains the help wiki permission entity class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Entity/Permission.php
 * @package    Mesd\HelpWikiBundle\Entity
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permission
 */
class Permission
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Mesd\HelpWikiBundle\Entity\page
     */
    private $page;

    /**
     * @var \Mesd\HelpWikiBundle\Model\RoleSubjectInterface
     */
    private $role;

    /**
     * @var string
     */
    private $permissionType;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set page
     *
     * @param \Mesd\HelpWikiBundle\Entity\page $page
     * @return Permission
     */
    public function setPage(\Mesd\HelpWikiBundle\Entity\page $page = null)
    {
        $this->page = $page;
    
        return $this;
    }

    /**
     * Get page
     *
     * @return \Mesd\HelpWikiBundle\Entity\page 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set role
     *
     * @param \Mesd\HelpWikiBundle\Model\RoleSubjectInterface $role
     * @return Permission
     */
    public function setRole(\Mesd\HelpWikiBundle\Model\RoleSubjectInterface $role = null)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return \Mesd\HelpWikiBundle\Model\RoleSubjectInterface
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set permissionType
     *
     * @param string $permissionType
     * @return Permission
     */
    public function setPermissionType($permissionType)
    {
        $this->permissionType = $permissionType;
    
        return $this;
    }

    /**
     * Get permissionType
     *
     * @return string
     */
    public function getPermissionType()
    {
        return $this->permissionType;
    }

    /**
     * To string
     *
     * @return string shortName
     */
    public function __toString()
    {
        return $this->getId();
    }
    /**
     * @var string
     */
    private $object;

    /**
     * @var string
     */
    private $type;


    /**
     * Set object
     *
     * @param string $object
     * @return Permission
     */
    public function setObject($object)
    {
        $this->object = $object;
    
        return $this;
    }

    /**
     * Get object
     *
     * @return string 
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Permission
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}