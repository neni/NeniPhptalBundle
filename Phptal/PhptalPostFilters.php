<?php

namespace Neni\PhptalBundle\Phptal;

class PhptalPostFilters implements \PHPTAL_Filter
{

    protected $filters;

    public function __construct($filtres)
    {
        $this->filters = $filtres;
    }


    function filter($source)
    {
        foreach($this->filters as $val){
            $_filtre = new $val['class']($val['params']);
            $source = $_filtre->filter($source);
        }
        return $source;
    }


    function filterDOM(\PHPTAL_Dom_Element $element)
    {
        foreach($this->filters as $val){
            $_filtre = new $val['class']($val['params']);
            $_filtre->filterDOM($element);
        }
    }
        

}
