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
class swResetLabelTranslation extends sfCallable
{
    
  public function __construct($callable)
  {
    if($callable instanceof self)
    {
      throw new sfException('callable cannot be an instance of swResetLabelTranslation');
    }

    $this->callable = $callable; 
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
        
    if (!is_callable($this->callable))
    {

      return sprintf($format, $subject); 
    }

    return sprintf($format,
      $this->callable instanceof sfCallable ?
      $this->callable->call($subject, $parameters, $catalogue) :
      call_user_func($this->callable, $subject, $parameters, $catalogue)
    );
  }
  
}