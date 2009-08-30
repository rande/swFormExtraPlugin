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
 * @author Thomas Rabaix <thomas.rabaix@gmail.com>
 *
 * SVN : $Id$
 **/
class swValidatorTrilean extends sfValidatorBoolean
{
  
  public function doClean($value)
  {
    if (in_array($value, $this->getOption('true_values')))
    {
      return true;
    }

    
    if (in_array($value, $this->getOption('false_values')))
    {
      return false;
    }
     
    
    return null;
  }
  
  public function isEmpty($value)
  {

    return false;
  }
}