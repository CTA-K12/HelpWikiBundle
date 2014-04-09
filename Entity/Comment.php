<?php

namespace MESD\HelpWikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 */
class Comment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $body;

    /**
     * @var \DateTime
     */
    private $dateTime;

    /**
     * @var \MESD\HelpWikiBundle\Entity\Page
     */
    private $page;

    /**
     * @var \MESD\ORCase\CoreBundle\Entity\ORCaseUser
     */
    private $user;


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
     * Set body
     *
     * @param string $body
     * @return Comment
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     * @return Comment
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    
        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime 
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set page
     *
     * @param \MESD\HelpWikiBundle\Entity\Page $page
     * @return Comment
     */
    public function setPage(\MESD\HelpWikiBundle\Entity\Page $page = null)
    {
        $this->page = $page;
    
        return $this;
    }

    /**
     * Get page
     *
     * @return \MESD\HelpWikiBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set user
     *
     * @param \MESD\ORCase\CoreBundle\Entity\ORCaseUser $user
     * @return Comment
     */
    public function setUser(\MESD\ORCase\CoreBundle\Entity\ORCaseUser $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \MESD\ORCase\CoreBundle\Entity\ORCaseUser 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * To string
     *
     * @return string shortName
     */
    public function __toString()
    {
        return $this->getUser();
    }
}