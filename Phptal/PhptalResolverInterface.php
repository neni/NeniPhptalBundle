<?php

namespace Neni\PhptalBundle\Phptal;


interface PhptalResolverInterface extends \PHPTAL_SourceResolver
{
    /**
     * Returns PHPTAL_StringSource($string) or PHPTAL_FileSource($file)
     * public function resolve($path);
     */

    /**
     * Returns boolean : template exists
     */
    public function exists($name);
 
    /**
     * Returns boolean : templates is supported
     */
    public function supports($name);
    
}

