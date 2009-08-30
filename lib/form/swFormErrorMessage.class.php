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
class swFormErrorMessage extends swFormTranslableElement
{

  public function __toString()
  {

    $callable = sfWidgetFormSchemaFormatter::getTranslationCallable();

    if (!is_callable($callable) && !$callable instanceof sfCallable)
    {

      return sprintf($this->format, $this->label);
    }

    return sprintf($this->format, $callable instanceof sfCallable ? $callable->call($this->label, null, $this->catalogue) : call_user_func($callback, $this->label, null, $this->catalogue));
  }
}
