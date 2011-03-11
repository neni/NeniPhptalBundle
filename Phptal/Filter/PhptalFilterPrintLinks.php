<?php
/**
 * Insesrts list of external links used in the template
 * Usage: Put <div id="printlinks"></div> in your template
 * and use CSS to show it only in print mode
 */

namespace Neni\PhptalBundle\Phptal\Filter;

require_once 'PHPTAL\Filter.php';

class PhptalFilterPrintLinks implements \PHPTAL_Filter
{
    protected $_link_list = '';

    public function filter($xhtml) {
        $link_count = 1;
        $this->_link_list = '';
        $xhtml = preg_replace ('/<a[^>]*href="([^"]+)"[^>]*>(.+?)<\/a>/ie', '$this->_build_link_list($link_count++, "\\1", "\\0")', $xhtml);
        $xhtml = preg_replace ('@<div id="printlinks"></div>@i', '<ol id="printlinks">' . $this->_link_list . '</ol>', $xhtml);
        return $xhtml;
    }

    protected function _build_link_list($link_count, $link, $a) {
        // if link to external site
        if (substr ($link, 0, 6) == 'ftps://' || substr ($link, 0, 5) == 'ftp://' || substr ($link, 0, 7) == 'http://' || substr ($link, 0, 8) == 'https://') {
            $this->_link_list .= "<li>$link</li>\n";
            return $a . '<sup class="printlinks">[' . $link_count . ']</sup>';
        } else {
            return $a;
        }
    }
}
