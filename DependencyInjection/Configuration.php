<?php

namespace Neni\PhptalBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;


class Configuration
{
    /**
     * Generates the configuration tree.
     *
     * @return \Symfony\Component\Config\Definition\NodeInterface
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('neni_phptal');
        $rootNode->children()->scalarNode('cache_warmer')->end()->end();
        
        $this->addPreFilterSection($rootNode);
        $this->addPostFilterSection($rootNode);
        $this->addPhptalOptions($rootNode);

        return $treeBuilder->buildTree();
    }



    private function addPreFilterSection(ArrayNodeDefinition $rootNode)
    {		
        $rootNode
            ->fixXmlConfig('pre_filter')
            ->children()
                ->arrayNode('pre_filters')
                    ->canBeUnset()
                    ->useAttributeAsKey('name')
                    ->prototype('array')    
                        ->fixXmlConfig('param')
                        ->children()
                             ->scalarNode('class')->end()
                             ->arrayNode('params')
                                ->beforeNormalization()
                                   ->ifTrue(function($v){ return !is_array($v); })
                                   ->then(function($v){ return array($v); })
                                ->end()
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();	
    }

    private function addPostFilterSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->fixXmlConfig('post_filter')
            ->children()
                ->arrayNode('post_filters')
                    ->canBeUnset()
                    ->useAttributeAsKey('name')
                    ->prototype('array')    
                        ->fixXmlConfig('param')
                        ->children()
                             ->scalarNode('class')->end()
                             ->arrayNode('params')
                                ->beforeNormalization()
                                   ->ifTrue(function($v){ return !is_array($v); })
                                   ->then(function($v){ return array($v); })
                                ->end()
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();	
    }

    private function addPhptalOptions(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->scalarNode('autoescape')->end()
            ->scalarNode('base_template_class')->end()
            ->scalarNode('cache_dir')->defaultValue('%kernel.cache_dir%/tal')->end()
            ->scalarNode('cache_lifetime')->defaultValue(30)->end()
            ->scalarNode('charset')->defaultValue('%kernel.charset%')->end()
            ->scalarNode('output_mode')->defaultValue('XHTML')->end()
            ->scalarNode('debug')->defaultValue('%kernel.debug%')->end()
            ->booleanNode('force_reparse')->defaultValue(false)->end()
            ->scalarNode('site_path')->defaultValue('%kernel.root_dir%/../web')->end()
            ->booleanNode('annotation')->defaultValue(false)->end()
            ->scalarNode('strict_variables')->end()
            ->scalarNode('auto_reload')->end();
    }
    
}
