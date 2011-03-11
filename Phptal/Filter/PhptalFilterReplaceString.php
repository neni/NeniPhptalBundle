<?php
/**
 * Replaces string in template code with another string
 *
 * @name My_PHPTAL_Filter_ReplaceString
 * @param  $search , $replace string
 */

namespace Neni\PhptalBundle\Phptal\Filter;

require_once 'PHPTAL\Filter.php';


class PhptalFilterReplaceString implements \PHPTAL_Filter
{
    protected $search = '';
    protected $replace = '';

    /**
     * Constructor
     *
     * @name __construct
     * @access public
     * @param  $search , $replace string
     */
    public function __construct($search, $replace) {
        $this->search = $search;
        $this->replace = $replace;
    } // end __construct();

    public function filter($xhtml) {
        $xhtml = str_replace ($this->search, $this->replace, $xhtml);
        return $xhtml;
    }

}

