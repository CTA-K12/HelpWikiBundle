<?php

namespace MESD\HelpWikiBundle\Entity;

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
     * @var \MESD\HelpWikiBundle\Entity\page
     */
    private $page;

    /**
     * @var \MESD\AuthenticationBundle\Entity\AuthRole
     */
    private $role;

    /**
     * @var \MESD\HelpWikiBundle\Entity\PermissionType
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
     * @param \MESD\HelpWikiBundle\Entity\page $page
     * @return Permission
     */
    public function setPage(\MESD\HelpWikiBundle\Entity\page $page = null)
    {
        $this->page = $page;
    
        return $this;
    }

    /**
     * Get page
     *
     * @return \MESD\HelpWikiBundle\Entity\page 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set role
     *
     * @param \MESD\AuthenticationBundle\Entity\AuthRole $role
     * @return Permission
     */
    public function setRole(\MESD\AuthenticationBundle\Entity\AuthRole $role = null)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return \MESD\AuthenticationBundle\Entity\AuthRole 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set permissionType
     *
     * @param \MESD\HelpWikiBundle\Entity\PermissionType $permissionType
     * @return Permission
     */
    public function setPermissionType(\MESD\HelpWikiBundle\Entity\PermissionType $permissionType = null)
    {
        $this->permissionType = $permissionType;
    
        return $this;
    }

    /**
     * Get permissionType
     *
     * @return \MESD\HelpWikiBundle\Entity\PermissionType 
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
        return $this->permissionType();
    }
}