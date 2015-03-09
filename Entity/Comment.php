<?php
/**
 * Comment.php file
 *
 * File that contains the help wiki comment entity class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Entity/Comment.php
 * @package    Mesd\HelpWikiBundle\Entity
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\HelpWikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mesd\HelpWikiBundle\Model\UserSubjectInterface;

/**
 * Comment
 */
class Comment
{
    const FLAGGED    = 'FLAGGED';
    const UNAPPROVED = 'UNAPPROVED';
    const APPROVED   = 'APPROVED';
    
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
     * @var status
     */
    private $status;

    /**
     * @var status date time
     */
    private $statusDateTime;

    /**
     * @var status by
     */
    private $statusBy;

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
     * Set status date time
     *
     * @param  \Date $statusDateTime
     * @return Page
     */
    public function setStatusDateTime($statusDateTime)
    {
        $this->statusDateTime = $statusDateTime;
    
        return $this;
    }

    /**
     * Get status date time
     *
     * @return \Date
     */
    public function getStatusDateTime()
    {
        return $this->statusDateTime;
    }

    /**
     * Set page
     *
     * @param \Mesd\HelpWikiBundle\Entity\Page $page
     * @return Comment
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
     * @return Comment
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
     * Set status by
     *
     * @param \Mesd\HelpWikiBundle\Model\UserSubjectInterface $statusBy
     * @return Comment
     */
    public function setStatusBy(\Mesd\HelpWikiBundle\Model\UserSubjectInterface $statusBy = null)
    {
        $this->statusBy = $statusBy;
    
        return $this;
    }

    /**
     * Get status by
     *
     * @return \Mesd\HelpWikiBundle\Model\UserSubjectInterface 
     */
    public function getStatusBy()
    {
        return $this->statusBy;
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