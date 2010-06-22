<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormTextareaMarkItUp represents a markItUp widget.
 *
 *
 * @package    symfony
 * @subpackage widget
 * @author     
 * @version    
 */
class swWidgetFormTextareaMarkItUp extends sfWidgetFormTextarea
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * settings:  The markItUp settings
   *  * set: The markItUp set
   *  * skin: The markItUp skin
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('settings', array(
      'nameSpace' => 'html'
    ));
    
    $this->addOption('set_name', 'default');
    $this->addOption('skin_name', 'simple');
    $this->addOption('extra_javascripts', array());
    $this->addOption('extra_stylesheets', array());
        
    parent::configure($options, $attributes);
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $textarea = parent::render($name, $value, $attributes, $errors);
    
    $js = sprintf(<<<EOF
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function()	{
	jQuery('#%s').markItUp(%s);
});
//]]>
</script>
EOF
    , 
    $this->generateId($name),
    json_encode($this->getOption('settings'))
    );

    return $textarea . $js;
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    
    $stylesheets = array();
    if($this->getOption('skin_name'))
    {
      $stylesheets['/swFormExtraPlugin/js/markitup/skins/' . $this->getOption('skin_name') . '/style.css'] = 'all';
    }

    if($this->getOption('set_name'))
    {
      $stylesheets['/swFormExtraPlugin/js/markitup/sets/' . $this->getOption('set_name') . '/style.css'] = 'all';
    }
    
    return array_merge($stylesheets, $this->getOption('extra_stylesheets'));
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavascripts()
  {
    $javascripts = array();
    $javascripts[] = '/swFormExtraPlugin/js/markitup/jquery.markitup.js';
    
    if($this->getOption('set_name'))
    {
      $javascripts[] = '/swFormExtraPlugin/js/markitup/sets/' . $this->getOption('set_name') . '/set.js';
    }
    
    return array_merge($javascripts, $this->getOption('extra_javascripts'));
  }
}
