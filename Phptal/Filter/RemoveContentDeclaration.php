<?php
/**
 * Remove content type declaration
 * After removing content-type declaration you may do
 * content negiotiation to send valid XHTML
 *
 * for example:

 // remove content-type declaration from template
 $rct = new removeContentDeclaration();
 $phptal->setPreFilter($rct);

 // negotiate content depending to browser type
 // as described in http://www.doktorno.boo.pl/content_negotiation.php
 $xhtml = false;
 if (preg_match('/application\/xhtml\+xml(?![+a-z])(;q=(0\.\d{1,3}|[01]))?/i', $_SERVER['HTTP_ACCEPT'], $matches)) {
 $xhtmlQ = isset($matches[2])?($matches[2]+0.2):1;
 if (preg_match('/text\/html(;q=(0\d{1,3}|[01]))s?/i', $_SERVER['HTTP_ACCEPT'],  $matches)) {
 $htmlQ = isset($matches[2]) ? $matches[2] : 1;
 $xhtml = ($xhtmlQ >= $htmlQ);
 } else {
 $xhtml = true;
 }
 }

 if ($xhtml) {
 header('Content-Type: application/xhtml+xml; charset=utf-8');
 } else {
 header('Content-Type: text/html; charset=utf-8');
 }

 echo $phptal->execute();

 */

namespace Neni\PhptalBundle\Phptal\Filtre;

class RemoveContentDeclaration implements \PHPTAL_Filter {


    public function filter($xhtml) {
        $xhtml = preg_replace('/<meta[\s]+(.*)content-type[^>]+>/ie', '', $xhtml, 1);
        return $xhtml;
    }

}

