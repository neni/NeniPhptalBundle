<?php
/**
 * Replaces string in template code with another string
 *
 * @name My_PHPTAL_Filter_ReplaceString
 * @param  $search , $replace string
 */

namespace Neni\PhptalBundle\Phptal\Filter;



class RemoveComments extends \PHPTAL_PreFilter
{


    function filterDOM(\PHPTAL_Dom_Element $element)
    {
        foreach($element->childNodes as $node) {
           if ($node instanceof \PHPTAL_Dom_Comment) {
               $node->parentNode->removeChild($node);
           }
           else if ($node instanceof \PHPTAL_Dom_Element) {
               $this->filterDOM($node);
           }
        }
    }


}

