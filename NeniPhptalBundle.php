<?php

namespace Neni\PhptalBundle;


use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Neni\PhptalBundle\DependencyInjection\Compiler\NeniPhptalWriterPass;


class NeniPhptalBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        //$container->addCompilerPass(new NeniPhptalWriterPass());
        //$container->registerExtension(new DependencyInjection\NeniPhptalExtension());
    }

}
