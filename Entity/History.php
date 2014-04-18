<?php

namespace Mesd\HelpWikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mesd\HelpWikiBundle\Model\UserSubjectInterface;

/**
 * History
 */
class History
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var integer
     */
    private $revision;

    /**
     * @var \DateTime
     */
    private $dateTime;

    /**
     * @var \Mesd\HelpWikiBundle\Entity\Page
     */
    private $page;

    /**
     * @var \Mesd\HelpWikiBundle\Model\UserSubjectInterface
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
     * Set title
     *
     * @param string $title
     * @return History
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return History
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
     * Set slug
     *
     * @param string $slug
     * @return History
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set revision
     *
     * @param integer $revision
     * @return History
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;
    
        return $this;
    }

    /**
     * Get revision
     *
     * @return integer 
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     * @return History
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
     * @param \Mesd\HelpWikiBundle\Entity\Page $page
     * @return History
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
     * Set user
     *
     * @param \Mesd\HelpWikiBundle\Model\UserSubjectInterface $user
     * @return History
     */
    public function setUser(\Mesd\HelpWikiBundle\Model\UserSubjectInterface $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Mesd\HelpWikiBundle\Model\UserSubjectInterface 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @ORM\PreUpdate
     */
    public function historyCreateAction(\Mesd\HelpWikiBundle\Entity\Page $page)
    {
        $this->title    = $page->getTitle();
        $this->body     = $page->getBody();
        $this->slug     = $page->getSlug();
        $this->revision = $page->getUser();
        $this->dateTime = $page->getDateTime();
        $this->page     = $page->getId();
        $this->user     = $page->getUser();
        var_dump($this);exit;
    }

    /**
     * To string
     *
     * @return string shortName
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}