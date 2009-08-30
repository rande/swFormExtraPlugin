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
 *
 * @package    swFormExtraPlugin
 * @subpackage form 
 * @author     Thomas Rabaix <thomas.rabaix@soleoweb.com>
 * @version    SVN: $Id$
 */
class swFormDynamicResult
{

  public $value = null;
  public $options  = array();

  public function __construct($value, $options = array())
  {
    $this->value = $value;
    $this->options  = $options;
  }
  
  public function render($name, sfWidgetFormSchema $widgetSchema)
  {
    $options = $this->options;
    
    $values = array(
      'value' => $this->value,
    );
    
    if(isset($options['widget']))
    {
      $widget_name = $widgetSchema->generateName($name);
      $attributes = array(
        'name' => $widget_name
      );
      
      $values['html'] = $options['widget']->render($name, $this->value, $attributes);
      unset($options['widget']);
    }
    
    return array_merge($values, $options);
  }
}