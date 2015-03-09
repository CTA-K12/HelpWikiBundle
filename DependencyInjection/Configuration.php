<?php
/**
 * Configuration.php file
 *
 * File that contains the help wiki configuration class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/DependencyInjection/Configuration.php
 * @package    Mesd\HelpWikiBundle\DependencyInjection
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\HelpWikiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mesd_help_wiki');
            /*
            ->addDefaultsIfNotSet()
            ->children()
                permissions:
                    manage_all:
                    manage_permissions:
                    manage_pages:
                    manage_comments:
                    manage_links:
                    view_pages:
                    view_page_metadata:
                    view_comments:
                serivces:
                    jquery:
                    jquery_ui:
                    select_box:
                    wysiwyg_editor:
                    draggable_dropable:
                    user_interface:
                    role_interface:
                comments:
                    comments_on:
                    require_approval:
                    allow_flags:
                images:
                    temp_dir:
                    upload_dir:
                    download_dir:
                    file_manager_class:
                notifications:
                    notification_service:
                autosave: x seconds
                translation_domain:
                page_template:
                ckeditor:
                    format: html, md, bbc, wiki
                    allowed_tags:
                    etc...

            */
            /*
                ->scalarNode('user_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('login')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('revisit_behavior')
                            ->values(array('logout', 'redirect', 'status'))
                            ->defaultValue('status')
                        ->end()
                        ->scalarNode('revisit_redirect_target')
                            ->defaultValue(null)
                        ->end()
                        ->booleanNode('approval_mail')
                            ->defaultFalse()
                        ->end()
                        ->integerNode('token_ttl')
                            ->defaultValue(86400)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
                */

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        // 
        // Configuration Options
        // ---------------------
        // jquery_lib:
        // jquery_ui_lib:
        // select_box_form_type:
        // textarea_form_type:
        $rootNode
            ->children()
                ->booleanNode('security')
                    ->defaultValue(false)
                ->end()
                ->arrayNode('super_admin_roles')
                    ->prototype('scalar')->end()
                    ->defaultValue(array())
                ->end()
                ->arrayNode('form_types')
                    ->children()
                        ->scalarNode('wysiwyg_editor')
                            ->defaultValue('mesd_help_wiki_ckeditor')
                        ->end()
                        ->scalarNode('selectbox_editor')
                            ->defaultValue('mesd_help_wiki_select2')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
