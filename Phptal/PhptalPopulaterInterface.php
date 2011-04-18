<?php

namespace Neni\PhptalBundle\Phptal;

interface PhptalPopulaterInterface
{
    /**
     * add data to template
     */
    public function populate($template, $values);
    
}

