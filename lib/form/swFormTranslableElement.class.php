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
class swFormTranslableElement
{
  protected
    $label,
    $format,
    $catalogue;
    
  public function __construct($label, $catalogue = null, $format = "%s")
  {
    $this->label = $label;
    $this->format = $format;
    $this->catalogue = $catalogue;
  }

  public function __toString()
  {

    return $this->label;
  }

  public function getLabel()
  {

    return $this->label;
  }

  public function getFormat()
  {

    return $this->format;
  }

}
