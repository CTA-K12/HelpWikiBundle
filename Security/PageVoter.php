<?php
/**
 * PageVoter.php file
 *
 * File that contains the help wiki page voter class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Security/PageVoter.php
 * @package    Mesd\HelpWikiBundle\Security
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

/**
 * Page voter
 *
 * Voter for page object permissions
 *
 * @package    Mesd\HelpWikiBundle\Security
 * @copyright  2015 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.1.0
 */
class PageVoter implements VoterInterface
{
    const CREATE   = 'CREATE';
    const VIEW     = 'VIEW';
    const EDIT     = 'EDIT';
    const DELETE   = 'DELETE';
    const RESTORE  = 'RESTORE';
    const FLAG     = 'FLAG';
    const APPROVE  = 'APPROVE';
    const RESTRICT = 'RESTRICT';
    const UPLOAD   = 'UPLOAD';
    const MANAGE   = 'MANAGE';

    /**
     * Container
     * 
     * @var Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    private $container;

    /**
     * Entity manager
     * 
     * @var Doctrine\ORM\EntityManager $entityManager
     */
    private $entityManager;

    /**
     * Constructor
     *
     * @author    Curtis G Hanson <chanson@mesd.k12.or.us>
     * @copyright 2015 MESD
     * @since     0.1
     * @param     ContainerInterface $container     [description]
     * @param     EntityManager      $entityManager [description]
     */
    public function __construct(ContainerInterface $container, EntityManager $entityManager)
    {
        $this->container     = $container;
        $this->entityManager = $entityManager;
    }

    /**
     * Supports attribute
     *
     * Test if attribute is supported
     *
     * @author    Curtis G Hanson <chanson@mesd.k12.or.us>
     * @copyright 2015 MESD
     * @since     0.1
     * @param     string  $attribute The attribute to test
     * @return    boolean
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::CREATE,
            self::VIEW,
            self::EDIT,
            self::DELETE,
            self::RESTORE,
            self::FLAG,
            self::APPROVE,
            self::RESTRICT,
            self::UPLOAD,
            self::MANAGE,
        ));
    }

    /**
     * Supports class
     *
     * Test if class is supported
     *
     * @author    Curtis G Hanson <chanson@mesd.k12.or.us>
     * @copyright 2015 MESD
     * @since     0.1
     * @param     string  $class The FQCN
     * @return    boolean
     */
    public function supportsClass($class)
    {
        $supportedClass = 'Mesd\HelpWikiBundle\Entity\Page';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * Vote
     *
     * 
     * @var \MesdHelpWikiBundle\Entity\Page $page
     */
    public function vote(TokenInterface $token, $page, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass(get_class($page))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // check whether security is turned on or off
        // if it's off, bypass the whole voter and grant access
        if(!$this->container->getParameter('mesd_help_wiki.security')) {
            return VoterInterface::ACCESS_GRANTED;
        }

        // check if the given attributes are covered by this voter
        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                return VoterInterface::ACCESS_ABSTAIN;
            }
        }

        // get current logged in user
        $user = $token->getUser();

        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        // get if a super role was entered
        if ($this->container->hasParameter('mesd_help_wiki.super_admin_roles')) {
            $roles = $this->container->getParameter('mesd_help_wiki.super_admin_roles');
        }

        // check user has one of the super roles
        if (isset($roles)) {
            foreach ($roles as $role) {
                if (in_array($role, $user->getRoles())) {
                    return VoterInterface::ACCESS_GRANTED;
                }
            }
        }

        // get object permissions
        $permissions = $this->entityManager->getRepository('MesdHelpWikiBundle:Permission')->findByObject('PAGE');

        // check user has permissions as set for object and role
        foreach ($permissions as $permission) {
            if (in_array($permission->getRole()->getRole(), $user->getRoles())) {
                if (in_array($permission->getType(), $attributes)) {
                    return VoterInterface::ACCESS_GRANTED;
                }
            }
        }

        // deny them if all above fail
        return VoterInterface::ACCESS_DENIED;
    }
}
