<?php

namespace Sensio\Bundle\FrameworkExtraBundle\Tal;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * The TalListener class handles the @extra:Tal annotation.
 *
 */
class TalListener
{
    /**
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Guesses the template name to render and its variables and adds them to 
     * the request object.
     *
     * @param FilterControllerEvent $event A FilterControllerEvent instance
     */
    public function onCoreController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $request = $event->getRequest();

        if (!$configuration = $request->attributes->get('_tal')) {
//echo "PAS DE _TAL";
            return;
        }


        if (!$configuration->getTemplate()) {
            $configuration->setTemplate($this->guessTemplateName($controller, $request, $configuration->getExtension() ));
        }

        $request->attributes->set('_tal', $configuration->getTemplate());
        $request->attributes->set('_tal_vars', $configuration->getVars());
        //$request->attributes->set('_tal_extension', $configuration->getExtension());


        // all controller method arguments
        if (!$configuration->getVars()) {
            $r = new \ReflectionObject($controller[0]);

            $vars = array();
            foreach ($r->getMethod($controller[1])->getParameters() as $param) {
                $vars[] = $param->getName();
            }

            $request->attributes->set('_tal_vars', $vars);
        }
    }

    /**
     * Renders the template and initializes a new response object with the 
     * rendered template content.
     *
     * @param GetResponseForControllerResultEvent $event A GetResponseForControllerResultEvent instance
     */
    public function onCoreView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $parameters = $event->getControllerResult();

        if (null === $parameters) {
            if (!$vars = $request->attributes->get('_tal_vars')) {
                if (!$vars = $request->attributes->get('_tal_default_vars')) {
                    return;
                }
            }

            $parameters = array();
            foreach ($vars as $var) {
                $parameters[$var] = $request->attributes->get($var);
            }
        }

        if (!is_array($parameters)) {
            return $parameters;
        }

        if (!$template = $request->attributes->get('_tal')) {
            return $parameters;
        }

        $event->setResponse(new Response($this->container->get('templating')->render($template, $parameters)));
    }

    /**
     * Guesses and returns the template name to render based on the controller 
     * and action names.
     *
     * @param array $controller An array storing the controller object and action method
     * @param Request $request A Request instance
     * @throws \InvalidArgumentException
     */
    protected function guessTemplateName($controller, Request $request, $ext)
    {
        if (!preg_match('/Controller\\\(.*)Controller$/', get_class($controller[0]), $match)) {
            throw new \InvalidArgumentException(sprintf('The "%s" class does not look like a controller class (it does not end with Controller)', get_class($controller[0])));
        }

        $bundle = $this->getBundleForClass(get_class($controller[0]));

        $name = $match[1].':'.substr($controller[1], 0, -6);

        return $bundle->getName().':'.$name.'.'.$request->getRequestFormat().$ext; //.'.tal';
    }

    /**
     * Returns the Bundle instance in which the given class name is located.
     *
     * @param string $class A fully qualified controller class name
     * @param Bundle $bundle A Bundle instance
     * @throws \InvalidArgumentException
     */
    protected function getBundleForClass($class)
    {
        $namespace = strtr(dirname(strtr($class, '\\', '/')), '/', '\\');
        foreach ($this->container->get('kernel')->getBundles() as $bundle) {
            if (0 === strpos($namespace, $bundle->getNamespace())) {
                return $bundle;
            }
        }

        throw new \InvalidArgumentException(sprintf('The "%s" class does not belong to a registered bundle.', $class));
    }
}
