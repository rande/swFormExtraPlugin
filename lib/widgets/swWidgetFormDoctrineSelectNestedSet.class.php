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
 * Display a select with the NestedSet structure.
 * 
 * @author Thomas Rabaix <thomas.rabaix@soleoweb.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.soleoweb.com
 * 
 * SVN : $Id$
 */
class swWidgetFormDoctrineSelectNestedSet extends sfWidgetFormSelect
{

  public function __construct($options = array(), $attributes = array())
  {
    $options['choices'] = new sfCallable(array($this, 'getChoices'));

    
    parent::__construct($options, $attributes);
  }

  /**
   * Constructor.
   *
   * Available options:
   *
   *  * object:     The object class (required)
   *  * model:      The model class (required)
   *  * add_empty:  Whether to add a first empty value or not (false by default)
   *                If the option is not a Boolean, the value will be used as the text value
   *  * method:     The method to use to display object values (__toString by default)
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('object');

    $this->addOption('model', get_class($options['object']));
    $this->addOption('add_empty', false);
    $this->addOption('name_method', '__toString');
    $this->addOption('prefix', '-');
    $this->addOption('full_tree', false);

    parent::configure($options, $attributes);
  }
  
  /**
   * @return the choices from the root node
   */
  public function getChoices()
  {
     
    $object = $this->getOption('object');

    if(!$object instanceof sfDoctrineRecord)
    {
      throw new sfException('The object must be an instance of sfDoctrineRecord');
    }

    if(!$object->getTable()->hasTemplate('Doctrine_Template_NestedSet'))
    {
      throw new sfException('The object is not a Doctrine_Template_NestedSet');
    }

    $root = Doctrine::getTable($this->getOption('model'))->getTree()->findRoot();

    if(!$root)
    {
      
      return array();
    }
    
    
    $choices = array( $root->getId() => $this->formatLabel($root, 0) );

    if(!$object->getNode()->isRoot() || $this->getOption('full_tree'))
    {
      $this->buildChoices($choices, $root);
    }

    return $choices;
  }

  /**
   * Build the array of choices
   *
   * @param array $choices
   * @param Doctrine_Record $root
   * @param integer $deep
   */
  protected function buildChoices(&$choices, $root, $deep = 0)
  {

    
    if(!$root->getNode()->hasChildren())
    {
      return;
    }
    
    
    foreach($root->getNode()->getChildren() as $child)
    {
      if(!$this->getOption('full_tree') && $this->getOption('object')->identifier() == $child->identifier())
      {
        
        continue;
      }
      
      $choices[$child->getId()] = $this->formatLabel($child, $deep + 1);

      $this->buildChoices($choices, $child, $deep + 1);
    }
  }

  /**
   * Format the option label
   *
   * @param Doctrine_Record $object
   * @param integer $deep
   * @return string
   */
  protected function formatLabel($object, $deep)
  {
    $name_method = $this->getOption('name_method');
    $prefix = $this->getOption('prefix');

    $name = call_user_func(array($object, $name_method));

    $label = str_repeat('&nbsp;&nbsp;', $deep).($deep > 0 ? '' : '').str_repeat($prefix, $deep)." ".$name;

    return $label;
  }
  
  /**
   * 
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $choices = $this->getOption('choices');
    if ($choices instanceof sfCallable)
    {
      $choices = $choices->call();
    }

    return $this->renderContentTag('select', "\n".implode("\n", $this->getOptionsForSelect($value, $choices))."\n", array_merge(array('name' => $name), $attributes));
  }

}