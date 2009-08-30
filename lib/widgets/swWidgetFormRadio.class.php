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
 * swWidgetFormRadio represents one radio HTML tag.
 *
 * @package    swFormExtraPlugin
 * @subpackage widget
 * @author    Thomas Rabaix <thomas.rabaix@soleoweb.com>
 * @version    SVN: $Id$
 */
class swWidgetFormRadio extends sfWidgetForm
{

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
    $baseAttributes = array(
      'name'  => substr($name, 0, -2),
      'type'  => 'radio',
      'value' => self::escapeOnce($value),
      'id'    => $id = $this->generateId($name, self::escapeOnce($value)),
    );

    if($value)
    {
      $attributes['checked'] = 'checked';
    }
    
    return $this->renderTag('input', array_merge($baseAttributes, $attributes));
  }
}
