<?php

namespace Neni\PhptalBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;


class NeniPhptalExtension implements ExtensionInterface
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


        $container->setParameter('neni_phptal.options', $config);


        // annotation
        if($config['annotation']){
            $loader->load('annotation.xml');
        }

        // helper
        $loader->load('phptalHelpers.xml');

        /*
         $this->addClassesToCompile(array(
         'Neni\\PhptalBundle\\Phptal\\PhptalEngine'
         'Neni\\PhptalBundle\\Phptal\\TalHelper'
         ));
         */
       

    }


    public function getXsdValidationBasePath()
    {
        return null;
    }

    public function getNamespace()
    {
        return 'http://symfony.com/schema/dic/symfony';
    }


    public function getAlias()
    {
        return 'neni_phptal';
    }
}
