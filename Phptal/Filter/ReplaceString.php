<?php
/**
 * Replaces string in template code with another string
 *
 * @name My_PHPTAL_Filter_ReplaceString
 * @param  $search , $replace string
 */

namespace Neni\PhptalBundle\Phptal\Filter;



class ReplaceString extends \PHPTAL_PreFilter
{

    public function __construct($params) {
        $this->search = $params[0];
        $this->replace = $params[1];
    }

    public function filter($xhtml) {
        $xhtml = str_replace ($this->search, $this->replace, $xhtml);
        return $xhtml;
    }

}

