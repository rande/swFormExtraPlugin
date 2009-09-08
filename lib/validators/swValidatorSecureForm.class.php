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
 */
class swValidatorSecureForm extends sfValidatorBase
{

  protected
    $secure_validator = null,
    $original_validator = null
  ;
  
  public function __construct(sfValidatorBase $secure_validator , sfValidatorBase $original_validator)
  {

    $this->secure_validator   = $secure_validator;
    $this->original_validator = $original_validator;

    parent::__construct(array('required' => $original_validator->getOption('required')), array());
  }


  /**
   *
   * Call first the secure validator, which should escape wrong non valid html
   *  and then call the correct validator 
   * @param <type> $value
   * @return <type>
   */
  public function doClean($value)
  {

    $value = $this->secure_validator->clean($value);
    $value = $this->original_validator->clean($value);

    return $value;
  }
  
  // PROXY ALL METHODS TO THE ORIGINAL VALIDATOR


  /**
   *
   *
   * Returns an error message given an error code.
   *
   * @param  string $name  The error code
   *
   * @return string The error message, or the empty string if the error code does not exist
   */
  public function getMessage($name)
  {
    
    return $this->original_validator->getMessage($name);
  }

  /**
   * Adds a new error code with a default error message.
   *
   * @param string $name   The error code
   * @param string $value  The error message
   */
  public function addMessage($name, $value)
  {

    $this->original_validator->addMessage($name, $value);
  }

  /**
   * Changes an error message given the error code.
   *
   * @param string $name   The error code
   * @param string $value  The error message
   */
  public function setMessage($name, $value)
  {

    $this->original_validator->setMessage($name, $value);
  }

  /**
   * Returns an array of current error messages.
   *
   * @return array An array of messages
   */
  public function getMessages()
  {

    return $this->original_validator->getMessages();
  }

  /**
   * Changes all error messages.
   *
   * @param array $values  An array of error messages
   */
  public function setMessages($values)
  {
    
    $this->original_validator->setMessages($values);
  }

  /**
   * Gets an option value.
   *
   * @param  string $name  The option name
   *
   * @return mixed  The option value
   */
  public function getOption($name)
  {
    
    return $this->original_validator->getOption($name);
  }

  /**
   * Adds a new option value with a default value.
   *
   * @param string $name   The option name
   * @param mixed  $value  The default value
   */
  public function addOption($name, $value = null)
  {

    $this->original_validator->addOption($name, $value);
  }

  /**
   * Changes an option value.
   *
   * @param string $name   The option name
   * @param mixed  $value  The value
   */
  public function setOption($name, $value)
  {

    if($name == 'required')
    {
      $this->options[$name] = $value;
    }
    
    $this->original_validator->setOption($name, $value);
  }

  /**
   * Returns true if the option exists.
   *
   * @param  string $name  The option name
   *
   * @return bool true if the option exists, false otherwise
   */
  public function hasOption($name)
  {

    return $this->original_validator->hasOption($name);
  }

  /**
   * Returns all options.
   *
   * @return array An array of options
   */
  public function getOptions()
  {

    return $this->original_validator->getOptions();
  }

  /**
   * Changes all options.
   *
   * @param array $values  An array of options
   */
  public function setOptions($values)
  {

    $this->original_validator->setOptions($values);
  }

  /**
   * Adds a required option.
   *
   * @param string $name  The option name
   */
  public function addRequiredOption($name)
  {

    $this->original_validator->addRequiredOption($name);
  }

  /**
   * Returns all required option names.
   *
   * @param array An array of required option names
   */
  public function getRequiredOptions()
  {

    return $this->original_validator->getRequiredOptions();
  }

  /**
   * Returns default messages for all possible error codes.
   *
   * @return array An array of default error codes and messages
   */
  public function getDefaultMessages()
  {
    
    return $this->original_validator->getDefaultMessages();
  }

  /**
   * Sets default messages for all possible error codes.
   *
   * @param array $messages  An array of default error codes and messages
   */
  protected function setDefaultMessages($messages)
  {
    $this->original_validator->setDefaultMessages($messages);
  }

  /**
   * Returns default option values.
   *
   * @return array An array of default option values
   */
  public function getDefaultOptions()
  {

    return $this->original_validator->getDefaultOptions();
  }

  /**
   * Sets default option values.
   *
   * @param array $options  An array of default option values
   */
  protected function setDefaultOptions($options)
  {
    $this->original_validator->setDefaultOptions($options);
  }

  /**
   * Returns a string representation of this validator.
   *
   * @param  int $indent  Indentation (number of spaces before each line)
   *
   * @return string The string representation of the validator
   */
  public function asString($indent = 0)
  {

    return $this->original_validator->asString($indent);
  }

  /**
   * Returns all error messages with non default values.
   *
   * @return string A string representation of the error messages
   */
  protected function getMessagesWithoutDefaults()
  {

    return $this->original_validator->getMessagesWithoutDefaults();
  }

  /**
   * Returns all options with non default values.
   *
   * @return string  A string representation of the options
   */
  protected function getOptionsWithoutDefaults()
  {

    return $this->original_validator->getMessagesWithoutDefaults();
  }

}
