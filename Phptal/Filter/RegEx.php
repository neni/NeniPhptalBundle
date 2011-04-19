<?php
/**
 * Filter which replaces text matching regular expression
 *
 * @name My_PHPTAL_Filter_Regex
 * @param string|array $search
 * @param string|array $replace
 */

namespace Neni\PhptalBundle\Phptal\Filter;


class Regex implements \PHPTAL_Filter
{
    protected $search = '';
    protected $replace = '';

    /**
     * Constructor
     *
     * @name __construct
     * @access public
     * @param  array( $search , $replace string )
     */
    public function __construct($params) {
        $this->search = $params[0];
        $this->replace = $params[1];
    }

    /**
     * Method filter
     *
     * @name filter
     * @access public
     * @param  $xhtml string
     */
    public function filter($source) {
        $source = preg_replace ($this->search, $this->replace, $source, 1);
        return $source;
    }
} // end class replaceRegExFilter

