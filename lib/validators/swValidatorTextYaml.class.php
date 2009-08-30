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
class swValidatorTextYaml extends sfValidatorString
{

   public function doClean($value)
   {
     
     try {
       $yml = sfYaml::load($value);
     } 
     catch(Exception $e)
     {
       throw new sfValidatorError($this, $e->getMessage(), array('value' => $value));
     }
     
     return $yml;
   }
}