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
        
        //$this->addPreFilterSection($rootNode);
        //$this->addPostFilterSection($rootNode);
        $this->addPhptalOptions($rootNode);

        return $treeBuilder->buildTree();
    }



    private function addPreFilterSection(ArrayNodeDefinition $rootNode)
    {
		/*
        $rootNode
        ->fixXmlConfig('')
        ->arrayNode('pre_filter')
        ->prototype('scalar')
        ->beforeNormalization()
        ->ifTrue(function($v) { return is_array($v) && isset($v['id']); })
        ->then(function($v){ return $v['id']; })
        ->end()
        ->end()
        ->end()
        ;
		*/
    }

    private function addPostFilterSection(ArrayNodeDefinition $rootNode)
    {
		/*
        $rootNode
        ->fixXmlConfig('')
        ->arrayNode('post_filter')
        ->prototype('scalar')
        ->beforeNormalization()
        ->ifTrue(function($v) { return is_array($v) && isset($v['id']); })
        ->then(function($v){ return $v['id']; })
        ->end()
        ->end()
        ->end();
		*/
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
