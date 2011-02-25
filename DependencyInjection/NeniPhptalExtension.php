<?php

namespace Neni\PhptalBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

    
class NeniPhptalExtension extends Extension
{
    /**
     * Loads the Phptal configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('phptal.xml');
        
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->process($configuration->getConfigTree(), $configs);
        
        if (!empty($config['pre_filter'])) {
            foreach ($config['pre_filter'] as $id) {
                $container->getDefinition($id)->addTag('neni_phptal.pre_filter');
            }
        }
        
        if (!empty($config['post_filter'])) {
            foreach ($config['post_filter'] as $id) {
                $container->getDefinition($id)->addTag('neni_phptal.post_filter');
            }
        }
        
        // à quoi cela sert?
        if (!empty($config['cache_warmer'])) {
            $container->getDefinition('templating.cache_warmer.templates_cache')->addTag('kernel.cache_warmer');
        }
        
        /*
        unset(  
        );
        */
        
        $container->setParameter('neni_phptal.options', $config);
        
    }

    
    public function getXsdValidationBasePath()
    {
      return null;
    }
    
    public function getNamespace()
    {
      return 'http://www.symfony-project.org/schema/dic/symfony';
    }
    
    
    public function getAlias()
    {
        return 'neni_phptal';
    }
}
