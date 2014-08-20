<?php
// src/Mesd/HelpWikiBundle/DataFixtures/ORM/LoadRoleData.php

namespace Mesd\HelpWikiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Mesd\HelpWikiBundle\Entity\PermissionType;

class LoadRoleData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * HelpWikiBundle Permission Types
         *
         * There are 15 permissions in the help wiki bundle
         * that can be implemented via user supplied roles.
         * Permissions offer fine-grain control over all
         * aspects of user actions. The permissions are
         * set up hierarcically allowing admins the ability
         * to group common permissions.
         *
         * MANAGE_WIKI
         *     MANAGE_PAGES
         *         CREATE_PAGES
         *         EDIT_PAGES
         *         EDIT_PAGE_LOCKS
         *         DELETE_PAGES
         *         LINK_PAGES
         *         VIEW_PAGES
         *         VIEW_PAGE_DETAILS
         *         VIEW_PAGE_COMMENTS
         *     MANAGE_ALL_COMMENTS
         *         MANAGE_OWN_COMMENTS
         *             CREATE_OWN_COMMENTS
         *             EDIT_OWN_COMMENTS
         *             DELETE_OWN_COMMENTS
         *     MANAGE_FLAGS
         *     FLAG_PAGES
         *     FLAG_COMMENTS
         */

        // Add Manage Wiki Permission Type
        $manageWikiPermissionType = new PermissionType();

        $manageWikiPermissionType->setShortName('MANAGE_WIKI');
        $manageWikiPermissionType->setLongName('Manage Wiki Role');
        $manageWikiPermissionType->setDescription(
              'Allows a user to create, edit, delete, link, and view pages; '
            . 'edit page locks, view page details, and view page comments; '
            . 'create, edit, and delete own comments; '
            . 'edit and delete all other user comments; '
            . 'manage submitted flags for pages and comments suspect of violating a terms of use/service agreement; '
            . 'and submit flags for pages and comments suspect of violating a terms of use/service agreement.'
        );

        // Add Manage Pages Permission Type
        $managePagesPermissionType = new PermissionType();

        $managePagesPermissionType->setShortName('MANAGE_PAGES');
        $managePagesPermissionType->setLongName('Manage Pages Role');
        $managePagesPermissionType->setDescription(
              'Allows a user to create, edit, delete, link, and view pages. '
            . 'User may also edit page locks, view page details, view page comments.';
        );

        $this->addReference('permission-type-manage-pages', $managePagesPermissionType);

        // Add Create Pages Permission Type
        $createPagesPermissionType = new PermissionType();

        $createPagesPermissionType->setShortName('CREATE_PAGES');
        $createPagesPermissionType->setLongName('Create Pages Role');
        $createPagesPermissionType->setDescription('Allows a user to create pages.');

        $this->addReference('permission-type-create-pages', $createPagesPermissionType);

        // Add Edit Pages Permission Type
        $editPagesPermissionType = new PermissionType();

        $editPagesPermissionType->setShortName('EDIT_PAGES');
        $editPagesPermissionType->setLongName('Edit Pages Role');
        $editPagesPermissionType->setDescription('Allows a user to edit pages.');

        $this->addReference('permission-type-edit-pages', $editPagesPermissionType);

        // Add Delete Pages Permission Type
        $deletePagesPermissionType = new PermissionType();

        $deletePagesPermissionType->setShortName('DELETE_PAGES');
        $deletePagesPermissionType->setLongName('Delete Pages Role');
        $deletePagesPermissionType->setDescription('Allows a user delete pages.');

        $this->addReference('permission-type-delete-pages', $deletePagesPermissionType);

        // Add Link Pages Permission Type
        $linkPagesPermissionType = new PermissionType();

        $linkPagesPermissionType->setShortName('LINK_PAGES');
        $linkPagesPermissionType->setLongName('Link Pages Role');
        $linkPagesPermissionType->setDescription('Allows a user to create, edit (move), and delete page links.');

        $this->addReference('permission-type-link-pages', $linkPagesPermissionType);

        // Add View Pages Permission Type
        $viewPagesPermissionType = new PermissionType();

        $viewPagesPermissionType->setShortName('VIEW_PAGES');
        $viewPagesPermissionType->setLongName('View Pages Role');
        $viewPagesPermissionType->setDescription('Allows a user to view pages.');

        $this->addReference('permission-type-view-pages', $viewPagesPermissionType);

        // Add Delete Own Comments Permission Type
        $deleteOwnCommentsPermissionType = new PermissionType();

        $deleteOwnCommentsPermissionType->setShortName('DELETE_OWN_COMMENTS');
        $deleteOwnCommentsPermissionType->setLongName('Delete Self Created Comments Role');
        $deleteOwnCommentsPermissionType->setDescription('Allows a user to delete self created comments.');

        $this->addReference('permission-type-delete-own-comments', $deleteOwnCommentsPermissionType);

        // Add Edit Own Comments Permission Type
        $editOwnCommentsPermissionType = new PermissionType();

        $editOwnCommentsPermissionType->setShortName('EDIT_OWN_COMMENTS');
        $editOwnCommentsPermissionType->setLongName('Edit Self Created Comments Role');
        $editOwnCommentsPermissionType->setDescription('Allows a user to edit self created comments.');

        $this->addReference('permission-type-edit-own-comments', $editOwnCommentsPermissionType);

        // Add Create Comments Permission Type
        $createCommentsPermissionType = new PermissionType();

        $createCommentsPermissionType->setShortName('CREATE_COMMENTS');
        $createCommentsPermissionType->setLongName('Create Comments Role');
        $createCommentsPermissionType->setDescription('Allows a user to create comments.');

        $this->addReference('permission-type-create-comments', $createCommentsPermissionType);

        // Add Manage Own Comments Permission Type
        $manageOwnCommentsPermissionType = new PermissionType();

        $manageOwnCommentsPermissionType->setShortName('MANAGE_OWN_COMMENTS');
        $manageOwnCommentsPermissionType->setLongName('Manage Self Created Comments Role');
        $manageOwnCommentsPermissionType->setDescription('Allows a user to create, edit, and delete self created comments.');

        $this->addReference('permission-type-manage-own-comments', $manageOwnCommentsPermissionType);

        // Add Flag Comments Permission Type
        $flagCommentsPermissionType = new PermissionType();

        $flagCommentsPermissionType->setShortName('FLAG_COMMENTS');
        $flagCommentsPermissionType->setLongName('Flag Inappropriate Comments Role');
        $flagCommentsPermissionType->setDescription('Allows a user to flag comments that violate a terms of use/service agreement.');

        $this->addReference('permission-type-flag-comments', $flagCommentsPermissionType);

        // Add Flag Pages Permission Type
        $flagPagesPermissionType = new PermissionType();

        $flagPagesPermissionType->setShortName('FLAG_PAGES');
        $flagPagesPermissionType->setLongName('Flag Inappropriate Pages Role');
        $flagPagesPermissionType->setDescription('Allows a user to flag pages that violate a terms of use/service agreement.');

        $this->addReference('permission-type-flag-pages', $flagPagesPermissionType);

        // Persist permission types to database
        $manager->persist($manageWikiPermissionType);
        $manager->persist($managePagesPermissionType);
        $manager->persist($createPagesPermissionType);
        $manager->persist($editPagesPermissionType);
        $manager->persist($editPageLocksPermissionType);
        $manager->persist($deletePagesPermissionType);
        $manager->persist($linkPagesPermissionType);
        $manager->persist($viewPagesPermissionType);
        $manager->persist($viewPageDetailsPermissionType);
        $manager->persist($viewPageCommentsPermissionType);
        $manager->persist($manageAllCommentsPermissionType);
        $manager->persist($manageOwnCommentsPermissionType);
        $manager->persist($createCommentsPermissionType);
        $manager->persist($editOwnCommentsPermissionType);
        $manager->persist($deleteOwnCommentsPermissionType);
        $manager->persist($manageFlagsPermissionType);
        $manager->persist($flagPagesPermissionType);
        $manager->persist($flagCommentsPermissionType);

        $manager->flush();
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function getOrder()
    {
       return 1; 
    }
}