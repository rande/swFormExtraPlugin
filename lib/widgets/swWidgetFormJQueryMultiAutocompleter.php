<?php

/*
 * This file is part of the swFormExtraPlugin package.
 *
 * (c) 2008 Thomas Rabaix <thomas.rabaix@soleoweb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/**
 * swWidgetFormJQueryMultiAutocompleter represents an autocompleter input widget rendered by JQuery.
 * 
 * This autocompleter can be use to display multiple values.
 *
 * This widget needs JQuery to work.
 *
 * You also need to include the JavaScripts and stylesheets files returned by the getJavaScripts()
 * and getStylesheets() methods.
 *
 * If you use symfony 1.2, it can be done automatically for you.
 *
 * @package    swToolboxPlugin
 * @subpackage widgets
 * @author     Thomas Rabaix <thomas.rabaix@soleoweb.com>
 * @version    SVN: $Id$
 */
class swWidgetFormJQueryMultiAutocompleter extends sfWidgetFormInput
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * url:            The URL to call to get the choices to use (required)
   *  * config:         A JavaScript array that configures the JQuery autocompleter widget
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('url');
    $this->addOption('config', '{ }');

    parent::configure($options, $attributes);
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return $this->renderTag('input', array('type' => 'hidden', 'name' => $name, 'value' => $value)).
           parent::render('autocomplete_'.$name, $value, $attributes, $errors).
           sprintf(<<<EOF
<script type="text/javascript">
  jQuery("#%s")
  .autocomplete('%s', jQuery.extend({}, {
    dataType: 'json',
    multiple: true,
    parse:    function(data) {
      var parsed = [];
      for (key in data) {
        parsed[parsed.length] = { data: [ data[key], key ], value: data[key], result: data[key] };
      }
      return parsed;
    }
  }, %s))
  .result(function(event, data) { jQuery("#%s").val(jQuery(event.target).val()); });
  jQuery("#%s").keyup(function(event) { jQuery("#%s").val(jQuery(event.target).val()); });
</script>
EOF
      ,
      $this->generateId('autocomplete_'.$name),
      $this->getOption('url'),
      $this->getOption('config'),
      $this->generateId($name),
      $this->generateId('autocomplete_'.$name),
      $this->generateId($name)
      
    );
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array('/swFormExtraPlugin/js/autocompleter/jquery.autocompleter.css' => 'print');
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavascripts()
  {
    return array('/swFormExtraPlugin/js/autocompleter/jquery.autocompleter.js');
  }
}
