<?php

/**
 * PHPTAL filter to automatically add ID attributes
 * ID is added in format [first letter of the tag]+[next number]
 *
 * @author TAAT
 * @version 1.0
 */

 namespace Neni\PhptalBundle\Phptal\Filter;

 require_once 'PHPTAL\Filter.php';
 

 class PhptalFilterIds implements \PHPTAL_Filter 
 {
	 // PROPERTIES

	 /**
	  * Array of id’s
	  * @access private
	  * @var string
	  */
	 private $_ids = array();

	 /**
	 * Character encoding
	 * @access private
	 * @var string
	 */
	 private $_encoding = 'UTF-8';

	 /**
	 * Tags to add id to process
	 * @access private
	 * @var string
	 */
	 private $_tags = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ul', 'ol', 'blockquote', 'code', 'pre');

	 // PUBLIC METHODS

	 /**
	 * Set tags to process
	 * @access public
	 * @param string $params, [$param1...]
	 * @return object itself
	 */
	 public function setTags($params) {
		 if (is_array($params)) {
			 $this->_tags = $params;
		 } else {
			 $this->_tags = array();
			 foreach (func_get_args() as $arg) {
				 $this->_tags[] = (string) $arg;
			 }
		 }
		 return $this;
	 }

	 /**
	 * Filter string
	 * @access public
	 * @param string $xhtml
	 * @return null
	 */
	 public function filter($xhtml) {
		 $this->_dom = DOMDocument::loadXML($xhtml);
		 // $this->_dom->validate();
		 $this->_dom->encoding = $this->_encoding;
		 $this->_dom->preserveWhiteSpace = true;
		 foreach ($this->_tags as $tag) {
			 $this->_ids[$tag[0]] = 0;
			 $items =  $this->_dom->getElementsByTagName($tag);
			 foreach ($items as $item) {
				 $id = $item->getAttribute('id');
				 if (empty($id)) {
					 // check if attribute already exists
					 $existing = false;
					 do {
						 $this->_ids[$tag[0]]++;
						 $idvalue = $tag[0] . $this->_ids[$tag[0]];
						 $existing = $this->_dom->getElementById($idvalue);
					 } while ($existing);
					 $item->setAttribute('id', $idvalue);
				 }
			 }
		 }
		 $output = $this->_dom->saveXML();
		 // remove xml prolog
		 $output = preg_replace('@<\?xml[^\?]+\?>\n?@s', '', $output);
		 return $output;
	 }

 }

