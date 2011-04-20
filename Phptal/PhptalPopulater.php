<?php

namespace Neni\PhptalBundle\Phptal;

use Neni\PhptalBundle\Phptal\PhptalPopulaterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables;

// TODO: add globals and other things

class PhptalPopulater implements PhptalPopulaterInterface
{

    protected $container;
    protected $globals;

    public function __construct(ContainerInterface $container, GlobalVariables $globals)
    {
        $this->container = $container;
        $this->globals = $globals;
    }


    public function populate($template, $parameters)
    {
        $template->app = $this->globals;
        foreach ($parameters as $k=>$v){
            $template->$k = $v;
        }
        return $template;
    }


}

