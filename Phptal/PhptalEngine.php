<?php

namespace Neni\PhptalBundle\Phptal;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Templating\Loader\LoaderInterface;
use Symfony\Component\Templating\Storage\FileStorage;
use Symfony\Component\Templating\TemplateNameParserInterface;

use Neni\PhptalBundle\Phptal\PhptalHelper;

// TODO: improve the loader 
//require_once __DIR__. '../../../../vendor/Phptal-svn/classes/' .'PHPTAL.php';
require_once 'PHPTAL.php';



class PhptalEngine implements EngineInterface, \PHPTAL_SourceResolver
{
    protected $container;
    protected $parser;
    protected $loader;

    protected $encoder;

    protected $extensions = array();

    
    public function __construct(ContainerInterface $container, TemplateNameParserInterface $parser, LoaderInterface $loader, $options = array())
    {
        $this->container = $container;
        $this->parser  = $parser;
        $this->loader = $loader;
    }

    
    /**
     * Loads the templates.
     * @param string $name A template name
     * @return PHPTAL_FileSource 
     * @throws \InvalidArgumentException if the template cannot be found
     */
    public function resolve($name)
    {
       $source = $this->parser->parse($name);
       $source = $this->loader->load($source);
       if (false === $source) {
           throw new \InvalidArgumentException(sprintf('The template "%s" does not exist.', $name));
       }
       return new \PHPTAL_FileSource( $source->__toString() );
    }
    
    
    /**
     * Renders a template.
     * @param string $name       A template name
     * @param array  $parameters An array of parameters to pass to the template
     * @return string The evaluated template as a string
     *
     * @throws \InvalidArgumentException if the template does not exist
     * @throws \RuntimeException         if the template cannot be rendered
     */
    public function render($name, array $parameters = array())
    {

        $template = new \PHPTAL();

        // code destination
        $tmpdir = $this->container->getParameter('kernel.cache_dir') . '/phptal';
        if(!is_dir($tmpdir)){mkdir($tmpdir);}
        $template->setPhpCodeDestination($tmpdir);

        // SourceResolver
        $template->addSourceResolver($this);
        
        // encoding
        $template->setEncoding( $this->container->getParameter('kernel.charset') );
        
        
        // TODO: add phptal options
        // debug mode
        
        //$template->setForceReparse(true);
        
        // todo
        //$template->setOutputMode($mode);
                
        
        // set source template 
        $template->setTemplate($name);
        
        // set data
        //$template->data = $parameters;  
        foreach ($parameters as $k=>$v){
          $template->$k = $v;
        }
        
        // helpers
        $template->Helper = new PhptalHelper($this->container);
        
        // globals environnement
        //$template->Globals = new \Symfony\Bundle\TwigBundle\GlobalVariables($this->container);
                
        //
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
        try {
            $this->resolve($name);
        } catch (\InvalidArgumentException $e) {
            return false;
        }
        return true;
    }
    

    
    /**
     * Test if this class is able to render a template.
     * @param string $name A template name
     * @return boolean True if this class supports the given resource, false otherwise
     */
    public function supports($name)
    {
        return false !== strpos($name, '.tal.html');
    }

    
    /**
     * Renders a view and returns a Response.
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A Response instance
     * @return Response A Response instance
     */
    public function renderResponse($view, array $parameters = array(), Response $response = null)
    {
        if (null === $response) {
            $response = $this->container->get('response');
        }
        $response->setContent($this->render($view, $parameters));
        return $response;
    }

    
    
    
    
    
}
