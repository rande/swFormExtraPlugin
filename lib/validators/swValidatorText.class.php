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
 * Make sure the input is only text : a-Z, 0-1, "\n"
 * 
 * @author Thomas Rabaix <thomas.rabaix@gmail.com>
 * 
 * SVN : $Id$
 **/
class swValidatorText extends sfValidatorString
{

   public function doClean($value)
   {
     $value = (string) $value;
     $value = strip_tags($value); // remove tags
     
     $value = parent::doClean($value);
     
     return $value;
   }
}