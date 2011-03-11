<?php
/**
 * Filter which replaces text matching regular expression
 *
 * @name My_PHPTAL_Filter_Regex
 * @param string|array $search
 * @param string|array $replace
 */

namespace Neni\PhptalBundle\Phptal\Filter;

require_once 'PHPTAL\Filter.php';


class PhptalFilterRegex implements \PHPTAL_Filter
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

