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
 * Integrate Decoda into the swFormExtraPlugin
 */
class swDecoda extends Decoda
{
  
  public function __construct($string, $allowed = array()) 
  {
    $return = parent::__construct($string, $allowed);
    
    $this->__config['emoticonsPath'] = '/swFormExtraPlugin/images/emoticons/default';
    
    return $return;
  }
}