<?php

namespace Neni\PhptalBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class NeniPhptalWriterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        /*

        if (false === $container->hasDefinition('zend.logger')) {
        return;
        }

        $definition = $container->getDefinition('zend.logger');

        foreach ($container->findTaggedServiceIds('zend.logger.writer') as $id => $attributes) {
        $definition->addMethodCall('addWriter', array(new Reference($id)));
        }
        */
    }
}