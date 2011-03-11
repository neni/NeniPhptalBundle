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
use Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator;

require_once 'PHPTAL.php';

use Neni\PhptalBundle\Phptal\GenericHelper;



class Engine implements EngineInterface, \PHPTAL_SourceResolver
{
    protected $container;
    protected $parser;
    protected $locator;
    protected $options;


    public function __construct(ContainerInterface $container, TemplateNameParserInterface $parser, TemplateLocator $locator, $options)
    {
        $this->container = $container;
        $this->parser    = $parser;
        $this->locator   = $locator;
        $this->options   = $options;
    }

    /**
     * Find the templates.
     * @param string $name A template name
     * @return PHPTAL_FileSource
     * @throws \InvalidArgumentException if the template cannot be found
     */
    public function resolve($name)
    {
        $source = $this->parser->parse($name);
        try{
            $file = $this->locator->locate($source);
        } catch (\InvalidArgumentException $e) {
            $erreur = $e;
        }
        if (false === $file || null === $file) {
            throw new \InvalidArgumentException(sprintf('The template "%s" does not exist.', $name, 0, null, $erreur));
        }
        return new \PHPTAL_FileSource( $file );
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
    public function render($name, array $parameters = array(), $output_format=null)
    {

        $template = new \PHPTAL();

        // code cache destination
        $tmpdir = $this->options['cache_dir'];
        if(!is_dir($tmpdir)){mkdir($tmpdir);}
        $template->setPhpCodeDestination($tmpdir);

        // code cache durration
        $template->setCacheLifetime( $this->options['cache_lifetime'] );

        // encoding
        $template->setEncoding( $this->options['charset'] );

        // output mod
        if($output_format == null){
            $output_format = $this->options['output_mode'];
        }
        if($output_format=='XHTML'){
            $template->setOutputMode( \PHPTAL::XHTML );
        }elseif($output_format=='HTML5'){
            $template->setOutputMode( \PHPTAL::HTML5 );
        }elseif($output_format=='XML'){
            $template->setOutputMode( \PHPTAL::XML );
        }else{
            throw new \InvalidArgumentException('Unsupported output mode '.$output_format);
        }

        // force reparse (for debug prefilter)
        $template->setForceReparse( $this->options['force_reparse'] );

        // debug mode
        // ....

        // set SourceResolver
        $template->addSourceResolver($this);

        // set source template
        $template->setTemplate($name);

        // set data
        foreach ($parameters as $k=>$v){
            $template->$k = $v;
        }

        // helper
        $template->Helper = new genericHelper($this->container);

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
        return false !== strpos($name, '.tal');
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
