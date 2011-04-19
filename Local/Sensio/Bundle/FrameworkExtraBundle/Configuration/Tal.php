<?php


namespace Sensio\Bundle\FrameworkExtraBundle\Configuration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;


/**
 * The Template class handles the @extra:Tal annotation parts.
 *
 */
class Tal implements ConfigurationInterface
{

    /**
     * The template logic name.
     *
     * @var string
     */
    protected $template;

    /**
     * The associative array of template variables.
     *
     * @var array
     */
    protected $vars = array();


    protected $extension;


    /**
     * Returns the array of templates variables.
     *
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * Sets the template variables
     *
     * @param array $vars The template variables
     */
    public function setVars($vars)
    {
        $this->vars = $vars;
    }

    /**
     * Sets the template logic name.
     *
     * @param string $template The template logic name
     */
    public function setValue($template)
    {
        $this->setTemplate($template);
    }

    /**
     * Returns the template logic name.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Sets the template logic name.
     *
     * @param string $template The template logic name
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }


    public function setExtension($ext)
    {
        $this->extension = $ext;
    }
    public function getExtension()
    {
        return ($this->extension)?$this->extension:'.tal';
    }


    /**
     * Returns the annotation alias name.
     *
     * @return string
     * @see ConfigurationInterface
     */
    public function getAliasName()
    {
        return 'tal';
    }
}
