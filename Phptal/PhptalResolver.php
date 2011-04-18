<?php

namespace Neni\PhptalBundle\Phptal;

use Neni\PhptalBundle\Phptal\PhptalResolverInterface;

use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;


class PhptalResolver implements PhptalResolverInterface
{

    protected $parser;
    protected $locator;


    public function __construct(FileLocatorInterface $locator, TemplateNameParserInterface $parser)
    {
        $this->locator = $locator;
        $this->parser = $parser;
    }


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
        //return new \PHPTAL_FileSource( $file );
        return new \PHPTAL_StringSource( file_get_contents($file) );
    }


    public function exists($name)
    {
        try {
            $this->resolve($name);
        } catch (\InvalidArgumentException $e) {
            return false;
        }
        return true;
    }


    public function supports($name)
    {
        return false !== strpos($name, '.tal');
    }


}

