<?php
// src/MesdHelpWikiBundle/Security/PageVoter.php
namespace MesdHelpWikiBundle\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PageVoter implements VoterInterface
{
    const CREATE   = 'CREATE';
    const SHOW     = 'SHOW';
    const EDIT     = 'EDIT';
    const REMOVE   = 'REMOVE';
    const RESTORE  = 'RESTORE';
    const FLAG     = 'FLAG';
    const APPROVE  = 'APPROVE';
    const RESTRICT = 'RESTRICT';
    const UPLOAD   = 'UPLOAD';
    const MANAGE   = 'MANAGE';

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::VIEW,
            self::EDIT,
            self::CREATE,
            self::SHOW,
            self::EDIT,
            self::REMOVE,
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
        $supportedClass = 'MesdHelpWikiBundle\Entity\Page';

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

        switch($attribute) {
            case self::VIEW:
                // the data object could have for example a method isPrivate()
                // which checks the Boolean attribute $private
                if (!$post->isPrivate()) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;

            case self::EDIT:
                // we assume that our data object has a method getOwner() to
                // get the current owner user entity for this data object
                if ($user->getId() === $post->getOwner()->getId()) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}