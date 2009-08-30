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
 * 
 * @author Thomas Rabaix <thomas.rabaix@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.soleoweb.com
 * 
 * SVN : $Id$
 **/
class swValidatorStupidCaptcha extends sfValidatorBase
{

   public function doClean($value)
   {
     if(strlen($value) > 0)
     {
       throw new sfValidatorError($this, 'captcha', array('value' => $value,));
     }
     
     return $value;
   }
   
   public function isEmpty($value)
   {
     return !parent::isEmpty($value);
   }
}