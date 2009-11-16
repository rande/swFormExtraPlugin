<?php
/*
 * This file is part of the swFormExtraPlugin package.
 *
 * (c) 2008 Thomas Rabaix <thomas.rabaix@soleoweb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
include(dirname(__FILE__).'/../bootstrap/unit.php');

$t = new lime_test(9, new lime_output_color());

function myI18n($message, $values = array(), $catalogue = 'message')
{
 
  return "message:".$message.",catalogue:".$catalogue;
}

sfWidgetFormSchemaFormatter::setTranslationCallable('myI18n');

class EmbedTestForm extends sfForm
{
  public function configure()
  {
    $this->widgetSchema['preference1'] = new sfWidgetFormInput;
    $this->widgetSchema['preference2'] = new sfWidgetFormInput;
    
    $this->validatorSchema['preference1'] = new sfValidatorString(array('required' => true));
    $this->validatorSchema['preference1']->setMessage('required', 'the field is mandatory');
    $this->validatorSchema['preference2'] = new sfValidatorString(array('required' => true));
    
    swFormHelper::resetFormLabels($this,  array(// define settings for the label
      'prefix'                  => 'label_',
      'catalogue'               => 'nested_catalogue',
      'mandatory_format'        => "%s <sup>*</sup>",

      // define setting for the error message
      'error_message_prefix'    => 'error_message_',
      'error_message_catalogue' => 'nested_error_catalogue',
      'error_message_format'    => '<span class="error">%s</span>',
    ));
  }
}

class TestForm extends sfForm
{
  
  public function configure()
  {
    $this->widgetSchema['name'] = new sfWidgetFormInput;
    $this->widgetSchema['email'] = new sfWidgetFormInput;
    $this->widgetSchema['extra_field'] = new sfWidgetFormInput;
    
    
    $this->validatorSchema['name'] = new sfValidatorString(array('required' => true));
    $this->validatorSchema['email'] = new sfValidatorEmail(array('required' => true));
    $this->validatorSchema['extra_field'] = new sfValidatorString(array('required' => true));
    
    
    $this->embedForm('preferences', new EmbedTestForm);
    
    swFormHelper::useOnly($this, array('name', 'email', 'preferences'));
    
    swFormHelper::resetFormLabels($this,  array(// define settings for the label
      'prefix'                  => 'label_',
      'catalogue'               => 'catalogue',
      'mandatory_format'        => "%s <sup>*</sup>",

      // define setting for the error message
      'error_message_prefix'    => 'error_message_',
      'error_message_catalogue' => 'global_error_messages',
      'error_message_format'    => '<span class="error">%s</span>',
    ));
  }
}

$f = new TestForm;
$f->bind(array());

$nf = new EmbedTestForm;
$nf->bind(array());

$t->diag('Testing EmbedTestForm');
$t->cmp_ok(count($nf->getWidgetSchema()->getFields()), '==', 2, 'EmbedTestForm::getWidgetSchema() return 2 elements');
$t->cmp_ok(count($nf->getValidatorSchema()->getFields()), '==', 2, 'EmbedTestForm::getValidatorSchema() return 2 elements');
$t->cmp_ok($nf['preference1']->renderLabel(), '==', '<label for="preference1">message:label_preference1,catalogue:nested_catalogue <sup>*</sup></label>', 'EmbedTestForm::renderLabel() preference1 ok');

$t->diag('Testing TestForm');
$t->cmp_ok(count($f->getWidgetSchema()->getFields()), '==', 3, 'TestForm::getWidgetSchema() return 3 elements');
$t->cmp_ok(count($f->getValidatorSchema()->getFields()), '==', 3, 'TestForm::getValidatorSchema() return 3 elements');


$t->cmp_ok($f['email']->renderLabel(), '==', '<label for="email">message:label_email,catalogue:catalogue <sup>*</sup></label>', '::renderLabel() email ok');
$t->cmp_ok($f['preferences']['preference1']->renderLabel(), '==', '<label for="preferences_preference1">message:label_preference1,catalogue:catalogue <sup>*</sup></label>', '::renderLabel() nested preference ok');

$t->cmp_ok($f['email']->getError()->getMessage(), '==', '<span class="error">message:error_message_required,catalogue:global_error_messages</span>', '::getMessage() on email');
$t->cmp_ok($f['preferences']['preference1']->getError()->getMessage(), '==', '<span class="error">message:error_message_the_field_is_mandatory,catalogue:global_error_messages</span>', '::getMessage() on email');

