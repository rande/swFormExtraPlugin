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
class swFormHelper
{

  /**
   * As sf1.1.X, there is a bug with associative files upload
   * 
   * solution from http://fr2.php.net/manual/en/features.file-upload.multiple.php
   */
  static function convertFileInformation($taintedFiles)
  {
    // the bug has been fixed in 1.2.7
    // see http://trac.symfony-project.org/ticket/5075
    if(version_compare(SYMFONY_VERSION, '1.2.6', '>'))
    {
      
      return $taintedFiles;
    }
    
    $newOrdering = array();
    foreach(array_keys($taintedFiles) as $attr)
    {
       self::groupFileInfoByVariable($newOrdering, $taintedFiles[$attr], $attr);
    }
    
    return $newOrdering;
  }
  
  static private function groupFileInfoByVariable(&$top, $info, $attr) {
    if (is_array($info)) {
      foreach ($info as $var => $val) {
        if (is_array($val)) {
          self::groupFileInfoByVariable($top[$var], $val, $attr);
        } else {
          $top[$var][$attr] = $val;
        }
      }
    } else {
      $top[$attr] = $info;
    }

    return true;
  }
  
  /**
   * Reset the form label
   *
   * @return array $options used in the reset
   */
  static public function resetFormLabels(sfForm $form, array $options = array())
  {

    // label stuff, original purpose
    $options['prefix']           = isset($options['prefix']) ? $options['prefix'] : sfConfig::get('app_swToolbox_form_label_prefix', 'label_');
    $options['catalogue']        = isset($options['catalogue']) ? $options['catalogue'] : sfConfig::get('app_swToolbox_form_catalogue', false);
    $options['mandatory_format'] = isset($options['mandatory_format']) ? $options['mandatory_format'] : sfConfig::get('app_swToolbox_form_mandatory_format', '%s');
    $options['force_labels']     = isset($options['force_labels']) ? $options['force_labels'] : array();

    // error message settings
    $options['error_message_prefix']    = isset($options['error_message_prefix']) ? $options['error_message_prefix'] : sfConfig::get('app_swToolbox_form_error_message_prefix', null);
    $options['error_message_catalogue'] = isset($options['error_message_catalogue']) ? $options['error_message_catalogue'] : sfConfig::get('app_swToolbox_form_error_message_catalogue', null);
    $options['error_message_format']    = isset($options['error_message_format']) ? $options['error_message_format'] : sfConfig::get('app_swToolbox_form_error_message_format', '%s');

    $form->setOption('_sw_reset_options', $options);
    
    // define translation (where the magic runs)
    $callable = sfWidgetFormSchemaFormatter::getTranslationCallable();

    if(!$callable instanceof swResetLabelTranslation)
    {
      $proxier_callable = new swResetLabelTranslation($callable, $options['mandatory_format']);
      sfWidgetFormSchemaFormatter::setTranslationCallable($proxier_callable);
    }
    
    $callable = sfWidgetFormSchemaFormatter::getTranslationCallable();
    
    self::resetSchemaLabels($form->getWidgetSchema(), $form->getValidatorSchema(), $options);

    return $options;
  }
  
  static private function resetSchemaLabels(sfWidgetFormSchema $widget_schema, sfValidatorSchema $validator_schema, array $options)
  {
    if($options['catalogue'] !== false)
    {
      $widget_schema->getFormFormatter()->setTranslationCatalogue($options['catalogue']);
    }
    
    foreach($widget_schema->getFields() as $name => $child_widget_schema)
    { 
      $text_label = isset($options['force_labels'][$name]) ? $options['force_labels'][$name] : strtolower($options['prefix'].$name);

      // i18n label
      if(isset($validator_schema[$name]) && $validator_schema[$name]->getOption('required'))
      {
        $label = new swFormLabel($text_label, $options['catalogue'], $options['mandatory_format'], true);
      }
      else
      {
        $label = new swFormLabel($text_label);
      }
      
      $child_widget_schema->setLabel($label);

      // i18n error messages
      if($validator_schema[$name])
      {
        $messages = $validator_schema[$name]->getMessages();
        $validator_schema[$name]->setMessages(array());

        foreach($messages as $error_code => $message)
        {

          $message = $options['error_message_prefix'].strtolower(str_replace(array(',', "\"", "(", ")", ' ', '.'), array('_', "",  "_", "_",'_',''),$message));

          $form_error_message = new swFormErrorMessage($message, $options['error_message_catalogue'], $options['error_message_format']);

          $validator_schema[$name]->addMessage($error_code, $form_error_message);
        }
      }

      if($child_widget_schema instanceof sfWidgetFormSchema)
      {
        self::resetSchemaLabels($child_widget_schema, $validator_schema[$name], $options);
      }
    }
  }
  
  static public function generateJsonInformation($form)
  {
    $errors = array();
    $bound = $form->isBound();
    
    if(count($form->getErrorSchema()->getErrors()) > 0)
    {
      foreach($form->getErrorSchema()->getErrors() as $field => $error)
      {
        $errors[$field] = $error->getMessage();
      }
    }
    
    $information = array(
      'hasErrors' => count($form->getErrorSchema()->getErrors()) > 0 ? true : false,
      'errors' => $errors,
      'bound'  => $bound,
      'values' => $form->getValues(),
      //'default' => $form->getDefaults(),
    );
    
    return json_encode($information);
  }
  
  
  /**
   * Force the form to only use specifics fieds and optionaly reorder the form
   *
   * @param sfForm $form
   * @param array $fields
   * @param boolan $use_order
   */
  public static function useOnly(sfForm $form, array $fields = array(), $use_order = false)
  {
    
    foreach($form as $field => $widget)
    {
      if(!in_array($field, $fields) && !in_array($field, array('sort_by', 'sort_order')))
      {

        $form->offsetUnset($field);

        continue;
      }
    }
    
    if($use_order)
    {
      foreach($fields as $pos => $field)
      {
        $form->getWidgetSchema()->moveField($field, sfWidgetFormSchema::LAST);
      }
    }
  }
}