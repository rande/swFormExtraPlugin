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
 * @package    swToolbox
 * @subpackage form
 * @author     Thomas Rabaix <thomas.rabaix@soleoweb.com>
 * @version    SVN: $Id$
 */
class swFormLabel extends swFormTranslableElement
{
  protected
    $required;
  
  public function __construct($label, $catalogue = null, $format = "%s", $required = false )
  {
    parent::__construct($label, $catalogue, $format);

    $this->required = $required;
  }
  
  public function isRequired()
  {
    
    return $this->required;
  }
}