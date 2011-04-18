<?php

namespace Neni\PhptalBundle\Phptal;

use Neni\PhptalBundle\Phptal\PhptalPopulaterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


// TODO: add globals and other things

class PhptalPopulater implements PhptalPopulaterInterface
{

    protected $container;
    

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function populate($template, $parameters)
    {
        foreach ($parameters as $k=>$v){
            $template->$k = $v;
        }
        return $template;
    }


}

