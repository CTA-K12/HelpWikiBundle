<?php
/**
 * History.php file
 *
 * File that contains the help wiki history entity class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Entity/History.php
 * @package    Mesd\HelpWikiBundle\Entity
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
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
     * @var string
     */
    private $status;

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
     * Set status
     *
     * @param  string $status
     * @return Page
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
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