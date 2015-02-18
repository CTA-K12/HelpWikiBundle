<?php

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

        $rootNode
            ->children()
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
