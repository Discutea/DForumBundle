<?php

namespace Discutea\DForumBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('discutea');

        $rootNode
            ->children()
                ->arrayNode('antiflood')
                    ->children()
                        ->booleanNode('enabled')->defaultValue(true)->end()
                        ->integerNode('hours')->defaultValue(12)->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('preview')
                    ->children()
                        ->booleanNode('enabled')->defaultValue(true)->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('knp_paginator')
                    ->children()
                        ->scalarNode('page_name')->end()
                        ->arrayNode('topics')
                            ->children()
                                ->booleanNode('enabled')->defaultValue(true)->end()
                                ->integerNode('per_page')->min(0)->end()
                            ->end()
                        ->end()
                        ->arrayNode('posts')
                            ->children()
                                ->booleanNode('enabled')->defaultValue(true)->end()
                                ->integerNode('per_page')->min(0)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}
