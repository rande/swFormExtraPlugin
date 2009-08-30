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
 * Validate the form
 * 
 * @author Thomas Rabaix <thomas.rabaix@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.soleoweb.com
 * 
 * SVN : $Id$
 **/
class swValidatorDoctrineNestedSet extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * model:      The model class (required)
   *  * query:      A query to use when retrieving objects
   *  * column:     The column name (null by default which means we use the primary key)
   *                must be in field name format
   *  * connection: The Doctrine connection to use (null by default)
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('object');
    $this->addOption('model', get_class($options['object']));
    $this->addOption('connection', null);
    $this->addOption('full_tree', false);
    
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $object = $this->getOption('object');

    $parent = Doctrine::getTable($this->getOption('model'))->find($value);
     
    if (!$parent)
    {
      throw new sfValidatorError($this, 'invalid parent reference', array('value' => $value));
    }

    if($this->getOption('full_tree'))
    {
      return $parent;
    }
    
    if($parent->getNode()->isDescendantOf($object))
    {
      throw new sfValidatorError($this, 'invalid change [1]', array('value' => $value));
    }

    if($parent->identifier() == $object->identifier() && !$object->getNode()->isRoot())
    {
      throw new sfValidatorError($this, 'invalid change [2]', array('value' => $value));
    }
    
    return $parent;
  }

}