<?php

namespace Mesd\HelpWikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Link
 */
class Link
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $routeAlias;

    /**
     * @var \Mesd\HelpWikiBundle\Entity\Page
     */
    private $page;


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
     * Set routeAlias
     *
     * @param string $routeAlias
     * @return Link
     */
    public function setRouteAlias($routeAlias)
    {
        $this->routeAlias = $routeAlias;
    
        return $this;
    }

    /**
     * Get routeAlias
     *
     * @return string 
     */
    public function getRouteAlias()
    {
        return $this->routeAlias;
    }

    /**
     * Set page
     *
     * @param \Mesd\HelpWikiBundle\Entity\Page $page
     * @return Link
     */
    public function setPage(\Mesd\HelpWikiBundle\Entity\Page $page = null)
    {
        $this->page = $page;
    
        return $this;
    }

    /**
     * Get page
     *
     * @return \Mesd\HelpWikiBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * To string
     *
     * @return string shortName
     */
    public function __toString()
    {
        return $this->getRouteAlias();
    }
}