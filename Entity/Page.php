<?php
/**
 * Page.php file
 *
 * File that contains the help wiki comment page class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Entity/Page.php
 * @package    Mesd\HelpWikiBundle\Entity
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mesd\HelpWikiBundle\Entity\History;
use Mesd\HelpWikiBundle\Model\UserSubjectInterface;

/**
 * Page
 */
class Page
{
    /**
     * ID
     * 
     * @var integer
     */
    private $id;

    /**
     * Title
     *
     * The title of a page
     * 
     * @var string
     */
    private $title;

    /**
     * Body
     *
     * The twig renderable body making a page
     * 
     * @var string
     */
    private $body;

    /**
     * Slug
     *
     * The url friendly name of a page
     * 
     * @var string
     */
    private $slug;

    /**
     * Revision
     *
     * An incremented integer starting at 0.
     * Indicated how many changes have been made to a page.
     * 
     * @var integer
     */
    private $revision;

    /**
     * Print Order
     *
     * The order of pages within a heirarchical tree.
     * Pages are unique in that they will not
     * have the same ordinal and the same parent
     *
     * Print order starts at 0
     * 
     * @var integer
     */
    private $printOrder;

    /**
     * Date Time
     *
     * The date/time of the current version of the page.
     * When it is changed, the date/time will also be updated.
     * 
     * @var \DateTime
     */
    private $dateTime;

    /**
     * Is Page Locked
     *
     * Users with MANAGE permissions may lock a page from edits.
     * 
     * @var boolean
     */
    private $pageLocked;

    /**
     * Comments Locked
     *
     * Users with MANAGE permission may lock a page from any additional
     * or changes to comments.
     * 
     * @var boolean
     */
    private $commentsLocked;

    /**
     * Edit In Progress
     *
     * If a page is currently being edited, the edit in progress
     * flag will be locked. At the session time (configurable option)
     * expiration, the page will be unlocked. This prevents users
     * from starting a page edit then leaving their computer
     * for an indefinite amount of time.
     * 
     * @var boolean
     */
    private $editInProgress;

    /**
     * Stand Alone
     *
     * If a page is stand-alone, it will not show up in the table of contents.
     * 
     * @var $standAlone
     */
    private $standAlone;

    /**
     * Status
     *
     * Options: DRAFT, PUBLISHED, UNPUBLISHED
     * varchar(16)
     * 
     * @var string
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $comments;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $permissions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $histories;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $links;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tags;

    /**
     * @var \Mesd\HelpWikiBundle\Entity\Page
     */
    private $parent;

    /**
     * @var \Mesd\HelpWikiBundle\Model\UserSubjectInterface
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->permissions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->histories   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->links       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags        = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * Set printOrder
     *
     * @param integer $printOrder
     * @return Page
     */
    public function setPrintOrder($printOrder)
    {
        $this->printOrder = $printOrder;
    
        return $this;
    }

    /**
     * Get printOrder
     *
     * @return integer 
     */
    public function getPrintOrder()
    {
        return $this->printOrder;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     * @return Page
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
     * Set Page Locked
     *
     * @param  boolean $pageLocked
     * @return Page
     */
    public function setPageLocked($pageLocked)
    {
        $this->pageLocked = $pageLocked;
    
        return $this;
    }

    /**
     * Get Page Locked
     *
     * @return boolean 
     */
    public function getPageLocked()
    {
        return $this->pageLocked;
    }

    /**
     * Is Page Locked
     *
     * @return boolean 
     */
    public function isPageLocked()
    {
        return $this->pageLocked;
    }

    /**
     * Set Comments Locked
     *
     * @param  boolean $commentsLocked
     * @return Page    $this
     */
    public function setCommentsLocked($commentsLocked)
    {
        $this->commentsLocked = $commentsLocked;
    
        return $this;
    }

    /**
     * Get Comments Locked
     *
     * @return boolean
     */
    public function getCommentsLocked()
    {
        return $this->commentsLocked;
    }

    /**
     * Is Comments Locked
     *
     * @return boolean
     */
    public function isCommentsLocked()
    {
        return $this->commentsLocked;
    }

    /**
     * Set Edit in Progress
     *
     * @param  boolean $editInProgress
     * @return Page    $this
     */
    public function setEditInProgress($editInProgress)
    {
        $this->editInProgress = $editInProgress;
    
        return $this;
    }

    /**
     * Get Edit in Progress
     *
     * @return boolean
     */
    public function getEditInProgress()
    {
        return $this->editInProgress;
    }

    /**
     * Is Edit in Progress
     *
     * @return boolean
     */
    public function isEditInProgress()
    {
        return $this->editInProgress;
    }

    /**
     * Set Stand Alone
     *
     * @param boolean $standAlone
     * @return Page
     */
    public function setStandAlone($standAlone)
    {
        $this->standAlone = $standAlone;
    
        return $this;
    }

    /**
     * Get Stand Alone
     *
     * @return boolean 
     */
    public function getStandAlone()
    {
        return $this->standAlone;
    }

    /**
     * Is Stand Alone
     *
     * @return boolean 
     */
    public function isStandAlone()
    {
        return $this->standAlone;
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
     * Add child
     *
     * @param \Mesd\HelpWikiBundle\Entity\Page $child
     * @return Page
     */
    public function addChild(\Mesd\HelpWikiBundle\Entity\Page $child)
    {
        $this->children[] = $child;
    
        return $this;
    }

    /**
     * Remove child
     *
     * @param \Mesd\HelpWikiBundle\Entity\Page $child
     */
    public function removeChild(\Mesd\HelpWikiBundle\Entity\Page $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set children
     *
     * @return $this
     */
    public function setChildren(\Doctrine\Common\Collections\Collection $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Add comments
     *
     * @param \Mesd\HelpWikiBundle\Entity\Comment $comments
     * @return Page
     */
    public function addComment(\Mesd\HelpWikiBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
    
        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Mesd\HelpWikiBundle\Entity\Comment $comments
     */
    public function removeComment(\Mesd\HelpWikiBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add permissions
     *
     * @param \Mesd\HelpWikiBundle\Entity\Permission $permissions
     * @return Page
     */
    public function addPermission(\Mesd\HelpWikiBundle\Entity\Permission $permissions)
    {
        $this->permissions[] = $permissions;
    
        return $this;
    }

    /**
     * Remove permissions
     *
     * @param \Mesd\HelpWikiBundle\Entity\Permission $permissions
     */
    public function removePermission(\Mesd\HelpWikiBundle\Entity\Permission $permissions)
    {
        $this->permissions->removeElement($permissions);
    }

    /**
     * Get permissions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Add history
     *
     * @param \Mesd\HelpWikiBundle\Entity\History $histories
     * @return Page
     */
    public function addHistory(\Mesd\HelpWikiBundle\Entity\History $history)
    {
        $this->histories[] = $history;
    
        return $this;
    }

    /**
     * Remove history
     *
     * @param \Mesd\HelpWikiBundle\Entity\History $histories
     */
    public function removeHistory(\Mesd\HelpWikiBundle\Entity\History $history)
    {
        $this->histories->removeElement($history);
    }

    /**
     * Get histories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistories()
    {
        return $this->histories;
    }

    /**
     * Add links
     *
     * @param \Mesd\HelpWikiBundle\Entity\Link $links
     * @return Page
     */
    public function addLink(\Mesd\HelpWikiBundle\Entity\Link $links)
    {
        $this->links[] = $links;
    
        return $this;
    }

    /**
     * Remove links
     *
     * @param \Mesd\HelpWikiBundle\Entity\Link $links
     */
    public function removeLink(\Mesd\HelpWikiBundle\Entity\Link $links)
    {
        $this->links->removeElement($links);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Set parent
     *
     * @param \Mesd\HelpWikiBundle\Entity\Page $parent
     * @return Page
     */
    public function setParent(\Mesd\HelpWikiBundle\Entity\Page $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Mesd\HelpWikiBundle\Entity\Page 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set user
     *
     * @param \Mesd\HelpWikiBundle\Model\UserSubjectInterface $user
     * @return Page
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
     * Add tag
     *
     * @param \Mesd\HelpWikiBundle\Entity\Tag $tag
     * @return Page
     */
    public function addTag(\Mesd\HelpWikiBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;
    
        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Mesd\HelpWikiBundle\Entity\Tag $tag
     */
    public function removeTag(\Mesd\HelpWikiBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
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
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $media;


    /**
     * Add children
     *
     * @param \Mesd\HelpWikiBundle\Entity\Page $children
     * @return Page
     */
    public function addChildren(\Mesd\HelpWikiBundle\Entity\Page $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \Mesd\HelpWikiBundle\Entity\Page $children
     */
    public function removeChildren(\Mesd\HelpWikiBundle\Entity\Page $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Add histories
     *
     * @param \Mesd\HelpWikiBundle\Entity\History $histories
     * @return Page
     */
    public function addHistorie(\Mesd\HelpWikiBundle\Entity\History $histories)
    {
        $this->histories[] = $histories;
    
        return $this;
    }

    /**
     * Remove histories
     *
     * @param \Mesd\HelpWikiBundle\Entity\History $histories
     */
    public function removeHistorie(\Mesd\HelpWikiBundle\Entity\History $histories)
    {
        $this->histories->removeElement($histories);
    }

    /**
     * Add media
     *
     * @param \Mesd\HelpWikiBundle\Entity\Media $media
     * @return Page
     */
    public function addMedia(\Mesd\HelpWikiBundle\Entity\Media $media)
    {
        $this->media[] = $media;
    
        return $this;
    }

    /**
     * Remove media
     *
     * @param \Mesd\HelpWikiBundle\Entity\Media $media
     */
    public function removeMedia(\Mesd\HelpWikiBundle\Entity\Media $media)
    {
        $this->media->removeElement($media);
    }

    /**
     * Get media
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedia()
    {
        return $this->media;
    }
}