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
 * swGMapSimpleValidator validate the address field
 *
 * @package    swToolboxPlugin
 * @subpackage widget
 * @author    Thomas Rabaix <thomas.rabaix@soleoweb.com>
 * @version    SVN: $Id$
 */
class swValidatorGMapAddress extends sfValidatorSchema
{
  public function __construct($fields = null, $options = array(), $messages = array())
  {
    $fields = array(
      'lat'     => new sfValidatorNumber(array(
        'min' => -90,  
        'max' => 90,
        'required' => true
      )),
      'lng'     => new sfValidatorNumber(array(
        'min' => -180, 
        'max' => 180,
        'required' => true
      )),
      'address' => new swValidatorText(array(
        'required' => true
      )),
    );
    
    parent::__construct($fields, $options, $messages);
  }
}