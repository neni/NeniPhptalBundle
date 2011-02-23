<?php

namespace Neni\PhptalBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;


use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
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
