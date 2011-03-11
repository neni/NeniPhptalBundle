<?php

namespace Neni\PhptalBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
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
        $rootNode = $treeBuilder->root('tal', 'array');

        $rootNode->scalarNode('cache_warmer')->end();

        $this->addPreFilterSection($rootNode);
        $this->addPostFilterSection($rootNode);
        $this->addPhptalOptions($rootNode);

        return $treeBuilder->buildTree();
    }


    private function addPreFilterSection(NodeBuilder $rootNode)
    {
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
    }

    private function addPostFilterSection(NodeBuilder $rootNode)
    {
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
    }

    private function addPhptalOptions(NodeBuilder $rootNode)
    {
        $rootNode
        ->scalarNode('autoescape')->end()
        ->scalarNode('base_template_class')->end()
        ->scalarNode('cache_dir')->addDefaultsIfNotSet()->defaultValue('%kernel.cache_dir%/tal')->end()
        ->scalarNode('cache_lifetime')->addDefaultsIfNotSet()->defaultValue(30)->end()
        ->scalarNode('charset')->addDefaultsIfNotSet()->defaultValue('%kernel.charset%')->end()
        ->scalarNode('output_mode')->addDefaultsIfNotSet()->defaultValue('XHTML')->end()
        ->scalarNode('debug')->addDefaultsIfNotSet()->defaultValue('%kernel.debug%')->end()
        ->scalarNode('force_reparse')->addDefaultsIfNotSet()->defaultValue(false)->end()
        ->scalarNode('strict_variables')->end()
        ->scalarNode('auto_reload')->end();
    }
    
}