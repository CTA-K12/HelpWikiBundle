<?php
/**
 * LinkVoter.php file
 *
 * File that contains the help wiki link voter class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Security/LinkVoter.php
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

class LinkVoter implements VoterInterface
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

    private $container;

    private $entityManager;

    public function __construct(ContainerInterface $container, EntityManager $entityManager)
    {
        $this->container     = $container;
        $this->entityManager = $entityManager;
    }

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

    public function supportsClass($class)
    {
        $supportedClass = 'Mesd\HelpWikiBundle\Entity\Link';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
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

        // check if the voter is used correct, only allow one attribute
        // this isn't a requirement, it's just one easy way for you to
        // design your voter
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed.'
            );
        }

        // set the attribute to check against
        $attribute = $attributes[0];

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
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
        
        if (isset($roles)) {
            foreach ($roles as $role) {
                if (in_array($role, $user->getRoles())) {
                    return VoterInterface::ACCESS_GRANTED;
                }
            }
        }

        $permissions = $this->entityManager->getRepository('MesdHelpWikiBundle:Permission')->findByObject('LINK');

        foreach ($permissions as $permission) {
            if (in_array($permission->getRole()->getRole(), $user->getRoles())) {
                if ($attribute === $permission->getType()) {
                    return VoterInterface::ACCESS_GRANTED;
                }
            }
        }

        return VoterInterface::ACCESS_DENIED;
    }
}