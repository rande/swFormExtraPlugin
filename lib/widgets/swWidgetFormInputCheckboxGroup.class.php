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
 * swWidgetFormInputCheckboxGroup represents a group of checkbox HTML tag 
 * for a array/callback where you can select multiple values.
 *
 * @package    swFormExtraPlugin
 * @subpackage widgets
 * @author     Thomas Rabaix <thomas.rabaix@soleoweb.com>
 * @version    SVN: $Id$
 */
class swWidgetFormInputCheckboxGroup extends sfWidgetFormSelectMany
{

  /**
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('clone_callable', true);
    
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $choices = $this->getOption('choices');
    if ($choices instanceof sfCallable)
    {
      $choices = $choices->call();
    }
    
    $separator = "\n";
    if(array_key_exists('separator', $attributes))
    {
      $separator = $attributes['separator']."\n";

      return $this->renderContentTag('div', "\n".implode($separator, $this->getInputForCheckboxGroup($value, $choices, $name, $attributes))."\n", $attributes);
    }
    
    if(array_key_exists('format', $attributes))
    {
      $format = $attributes['format'];
      unset($attributes['format']);
      
      $inputs = array();
      
      foreach($this->getInputForCheckboxGroup($value, $choices, $name, $attributes) as $input)
      {
        $inputs[] = sprintf($format, $input);
      }
      
      return $this->renderContentTag('div', "\n".implode("\n", $inputs)."\n", $attributes);
    }
    
    
  }

  /**
   * Returns an array of input tags for the given choices
   *
   * @param  string $value    The selected value
   * @param  array  $choices  An array of choices
   * @param  string $name     The field name
   *
   * @return array  An array of option tags
   */
  protected function getInputForCheckboxGroup($value, $choices, $name, $attributes = array())
  {
    $mainAttributes = $this->attributes;
    $this->attributes = $attributes;

    
    $options = array();
    foreach ($choices as $key => $option)
    {
      $attributes = array(
        'value' => self::escapeOnce($key),
        'type'  => 'checkbox',
        'name'  => $name.'[]'
      
      );
      
      
      if ((is_array($value) && in_array(strval($key), $value)) || strval($key) == strval($value))
      {
        $attributes['checked'] = 'checked';
      }

      $options[] = $this->renderContentTag('input', self::escapeOnce($option), $attributes);
    }

    $this->attributes = $mainAttributes;

    return $options;
  }
  
  public function __clone()
  {
    
    if ($this->getOption('clone_callable') && $this->getOption('choices') instanceof sfCallable)
    {
      $callable = $this->getOption('choices')->getCallable();
      if (is_array($callable))
      {
        $callable[0] = $this;
        $this->setOption('choices', new sfCallable($callable));
      }
    }
  }
}