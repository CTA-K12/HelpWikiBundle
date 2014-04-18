<?php

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
     * @var \Mesd\AuthenticationBundle\Entity\AuthRole
     */
    private $role;

    /**
     * @var \Mesd\HelpWikiBundle\Entity\PermissionType
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
     * @param \Mesd\AuthenticationBundle\Entity\AuthRole $role
     * @return Permission
     */
    public function setRole(\Mesd\AuthenticationBundle\Entity\AuthRole $role = null)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return \Mesd\AuthenticationBundle\Entity\AuthRole 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set permissionType
     *
     * @param \Mesd\HelpWikiBundle\Entity\PermissionType $permissionType
     * @return Permission
     */
    public function setPermissionType(\Mesd\HelpWikiBundle\Entity\PermissionType $permissionType = null)
    {
        $this->permissionType = $permissionType;
    
        return $this;
    }

    /**
     * Get permissionType
     *
     * @return \Mesd\HelpWikiBundle\Entity\PermissionType 
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