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
 * sfWidgetFormRadio represents a select HTML tag : yes, no and null.
 *
 * @package    swFormExtraPlugin
 * @subpackage widget
 * @author    Thomas Rabaix <thomas.rabaix@soleoweb.com>
 * @version    SVN: $Id$
 */
class swWidgetFormTrilean extends sfWidgetFormChoice
{

  /**
   *
   * @see sfWidgetFormChoice
   */
  public function __construct($options = array(), $attributes = array())
  {
    
    $catalogue = isset($options['catalogue']) ? $options['catalogue'] : 'swToolbox';

    $choices = array(
      0 => __('label_no', null, $catalogue),
      1 => __('label_yes', null, $catalogue),
      '-' => __('label_not_defined', null, $catalogue)
    );
    
    $options['choices'] = isset($options['choices']) ? $options['choices'] : $choices;
    
    parent::__construct($options, $attributes);
  }

}
