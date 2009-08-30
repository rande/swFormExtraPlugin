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
class swResetLabelTranslation extends sfCallable
{
  static
    $callback;
    
  public function __construct($callable)
  {
    if($callable instanceof self)
    {
      throw new sfException('callable cannot be an instance of swResetLabelTranslation');
    }
    
    self::$callback = $callable;
  }
  
  /**
   * call the original callable method and add the formatting to it
   * (non-PHPdoc)
   * @see lib/vendor/symfony/lib/util/sfCallable#call()
   */
  
  public function call()
  {
    $arguments = func_get_args();
    
    $subject    = $arguments[0];
    $parameters = $arguments[1];
    $catalogue  = $arguments[2];
    $format     = "%s";
    
    if($subject instanceof swFormLabel)
    {
      $format  = $subject->isRequired() ? $subject->getFormat() : $format;
      $subject = $subject->getLabel();
    } 
    else if($subject instanceof swFormErrorMessage)
    {
      /*
       * Shame on me ... but there is no other choice ...
       *
       * Fabien, if you read me ...
       */
      return $subject->__toString();
    }

    if(method_exists($subject, 'getCatalogue') && is_null($subject->getCatalogue()))
    {
      $catalogue = $subject->getCatalogue();
    }
        
    if (!is_callable(self::$callback))
    {

      return sprintf($format, $subject); 
    }

    return sprintf($format, self::$callback instanceof sfCallable ? self::$callback->call($subject, $parameters, $catalogue) : call_user_func(self::$callback, $subject, $parameters, $catalogue));
  }
  
}