<?php


namespace Neni\PhptalBundle\Phptal\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\Helper\Helper ;

class PhptalGenericHelper extends Helper
{

    protected $container;
    protected $data;

    public function __construct(ContainerInterface $container, $data=null)
    {
        $this->container = $container;
        $this->data = $data;
    }

    // TODO: make cleaner?
    public function get($name)
    {
        // templating phptal helper
        if($this->container->has('tal.helper.'.$name)){
            return $this->container->get('tal.helper.'.$name);
        // assetic helper : todo all.
        }elseif('assetic'==$name){
            if($this->container->has('assetic.helper.dynamic')){
                return $this->container->get('assetic.helper.dynamic');
            }elseif($this->container->has('assetic.helper.static')){
                return $this->container->get('assetic.helper.static');
            }else{
                throw new \InvalidArgumentException(sprintf('The assetic helper does not exist.'));
            }
        // templating helper
        }elseif($this->container->has('templating.helper.'.$name)){
            return $this->container->get('templating.helper.'.$name);
        // templating twig helper
        }elseif($this->container->has('twig.extension.'.$name)){
            return $this->container->get('twig.extension.'.$name);
        }else{
            // not found
            throw new \InvalidArgumentException(sprintf('The helper "%s" does not exist.', $name));
        }
    }

    /* render a template */
    public function render($name)
    {
        return $this->container->get('templating')->render($name, $this->data);
    }


    public function getName()
    {
        return 'talhelper' ;
    }


}


