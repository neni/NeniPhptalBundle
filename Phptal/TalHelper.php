<?php


namespace Neni\PhptalBundle\Phptal;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\Helper\Helper ;

class TalHelper extends Helper
{
  
    protected $container;

    public function __construct(ContainerInterface $container)
    {
       $this->container = $container;
    }
    
    // TODO: make cleaner
    public function get($name)
    {
        // templating helper
        if($this->container->has('templating.helper.'.$name)){
            return $this->container->get('templating.helper.'.$name);
        // templating phptal helper
        }elseif($this->container->has('tal.helper.'.$name)){
            return $this->container->get('tal.helper.'.$name);
        // templating twig helper
        }elseif($this->container->has('twig.extension.'.$name)){
            return $this->container->get('twig.extension.'.$name);
        }else{
            // not found
            throw new \InvalidArgumentException(sprintf('The helper "%s" does not exist.', $name));
        }
    }

        
    
    public function getName()
    {
        return 'talhelper' ;
    }
  

}


