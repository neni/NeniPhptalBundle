<?php

namespace Neni\PhptalBundle\Phptal;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;


use Neni\PhptalBundle\Phptal\PhptalPopulaterInterface;
use Neni\PhptalBundle\Phptal\PhptalResolverInterface;
use Neni\PhptalBundle\Phptal\Helper\PhptalGenericHelper;


class PhptalEngine implements EngineInterface 
{
    protected $container;
    protected $resolver;
    protected $populater;
    protected $options;



    public function __construct(ContainerInterface $container, PhptalResolverInterface $resolver, PhptalPopulaterInterface $populater, $options)
    {
        $this->container = $container;
        $this->resolver = $resolver;
        $this->populater = $populater;
        $this->options   = $options;
    }


    /**
     * Renders a template.
     * @param string $name       A template name
     * @param array  $parameters An array of parameters to pass to the template
     *                           the default options engine can be override by $parameters['_engine_'][option]
     * @return string The evaluated template as a string
     *
     * @throws \InvalidArgumentException if the template does not exist
     * @throws \RuntimeException         if the template cannot be rendered
     */
    public function render($name, array $parameters = array() )
    {

        $template = new \PHPTAL();


        // engine options by parameters to relpace configuration
        if(isset($parameters['_engine_'])&&(is_array($parameters['_engine_']))){
            $options = $parameters['_engine_'];
        }else{
            $options = array();
        }

        // code cache destination
        $tmpdir = (isset($options['cache_dir']))?$options['cache_dir']:$this->options['cache_dir'];
        if(!is_dir($tmpdir)){mkdir($tmpdir);}
        $template->setPhpCodeDestination($tmpdir);

        // code cache durration
        $template->setCacheLifetime( (isset($options['cache_dir']))?$options['cache_lifetime']:$this->options['cache_lifetime'] );

        // encoding
        $template->setEncoding( (isset($options['charset']))?$options['charset']:$this->options['charset'] );
  
        // output mod
        if(!isset($options['output_format'])){
            $options['output_format'] = $this->options['output_mode'];
        }
        if($options['output_format']=='XHTML'){
            $template->setOutputMode( \PHPTAL::XHTML );
        }elseif($options['output_format']=='HTML5'){
            $template->setOutputMode( \PHPTAL::HTML5 );
        }elseif($options['output_format']=='XML'){
            $template->setOutputMode( \PHPTAL::XML );
        }else{
            throw new \InvalidArgumentException('Unsupported output mode ' . $options['output_format']);
        }

        // force reparse (for debug prefilter)
        $template->setForceReparse( (isset($options['force_reparse']))?$options['force_reparse']:$this->options['force_reparse'] );


        // pre filters
        $filtres = $this->options['pre_filters'];
        foreach($filtres as $filtre){
            $template->addPreFilter( new $filtre['class']($filtre['params']) );
        }

        // post filters
        $filtres = $this->options['post_filters'];
        if($filtres){
            $template->setPostFilter(new PhptalPostFilters($filtres));
        }

        // set SourceResolver
        if(!isset($options['resolver'])){
            $template->addSourceResolver($this->resolver);
        }else{
            if($this->container->has($options['resolver'])){
                $resolver = $this->container->get($options['resolver']);
                $r = new \ReflectionClass( get_class($resolver) );
                if (!$r->implementsInterface('Neni\\PhptalBundle\\Phptal\\PhptalResolverInterface')) {
                    throw new \InvalidArgumentException(sprintf('The service "%s" does implements PhptalResolverInterface.', $options['resolver']));
                }else{
                    $template->addSourceResolver( $resolver );
                }
            }else{
                throw new \InvalidArgumentException(sprintf('The service "%s" does not exist.', $options['resolver']));
            }
        }

        // set source template
        $template->setTemplate($name);


        // set data
        if(!isset($options['populater'])){
            unset($parameters['_engine_']);
            $this->populater->populate($template, $parameters);
        }else{
            if($this->container->has($options['populater'])){
                $populater = $this->container->get($options['populater']);
                $r = new \ReflectionClass( get_class($populater) );
                if (!$r->implementsInterface('Neni\\PhptalBundle\\Phptal\\PhptalPopulaterInterface')) {
                    throw new \InvalidArgumentException(sprintf('The service "%s" does implements PhptalPopulaterInterface.', $options['populater']));
                }else{
                    unset($parameters['_engine_']);
                    $populater->populate($template, $parameters);
                }
            }else{
                throw new \InvalidArgumentException(sprintf('The service "%s" does not exist.', $options['populater']));
            }
        }


        // generic helper
        $template->Helper = new PhptalGenericHelper($this->container, $parameters);

        // perform
        try{
            $result = $template->execute();
        }catch (PHPTAL_TemplateException $e){
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }

        return $result;
    }



    /**
     * Test if the template exists.
     * @param string $name A template name
     * @return Boolean true if the template exists, false otherwise
     */
    public function exists($name)
    {
        return $this->resolver->exists($name);
    }



    /**
     * Test if this class is able to render a template.
     * @param string $name A template name
     * @return boolean True if this class supports the given resource, false otherwise
     */
    public function supports($name)
    {
        return $this->resolver->supports($name);
    }


    /**
     * Renders a view and returns a Response.
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A Response instance
     * @return Response A Response instance
     */
    public function renderResponse($view, array $parameters = array(), Response $response = null )
    {
        if (null === $response) {
            $response = new Response();
        }
        $response->setContent($this->render($view, $parameters));
        return $response;
    }
}
