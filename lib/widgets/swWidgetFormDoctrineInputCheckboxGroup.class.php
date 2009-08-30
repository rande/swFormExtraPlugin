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
 * swWidgetFormDoctrineInputCheckboxGroup represents a group of checkbox HTML tag 
 * for a model where you can select multiple values.
 *
 * @package    swToolboxPlugin
 * @subpackage widgets
 * @author     Thomas Rabaix <thomas.rabaix@soleoweb.com>
 * @version    SVN: $Id$
 */
class swWidgetFormDoctrineInputCheckboxGroup extends sfWidgetFormDoctrineSelectMany
{
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
      unset($attributes['separator']);
    }
    
    return $this->renderContentTag('div', "\n".implode($separator, $this->getInputForCheckboxGroup($value, $choices, $name))."\n", $attributes);
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
  protected function getInputForCheckboxGroup($value, $choices, $name)
  {
    $mainAttributes = $this->attributes;
    $this->attributes = array();

    $options = array();
    foreach ($choices as $key => $option)
    {
      $attributes = array('value' => self::escapeOnce($key));
      if ((is_array($value) && in_array(strval($key), $value)) || strval($key) == strval($value))
      {
        $attributes['checked'] = 'checked';
      }

      $attributes['type'] = 'checkbox';
      $attributes['name'] = $name.'[]';

      $options[] = $this->renderContentTag('input', self::escapeOnce($option), $attributes);
    }

    $this->attributes = $mainAttributes;

    return $options;
  }
}