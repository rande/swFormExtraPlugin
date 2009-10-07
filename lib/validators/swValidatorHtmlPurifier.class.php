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
 * To use this widget, you must install HTMLPurifier available
 *  - at http://htmlpurifier.org
 *  - or via the sfXssSafePlugin http://www.symfony-project.org/plugins/sfXssSafePlugin
 *
 * Options :
 *   - configuration : an array of settings that will be use by html purifier
 *   - core_configuration : default settings for symfony project that will be use by html purifier
 * 
 * @author Thomas Rabaix <thomas.rabaix@gmail.com>
 *
 */
class swValidatorHtmlPurifier extends sfValidatorString
{

  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('bad_input', 'The input does not match the allowed settings.');

    $this->addOption('core_configuration', array(
      'Cache.DefinitionImpl' => 'Serializer',
      'Cache.SerializerPath' => sfConfig::get('sf_cache_dir'),
      'Core.Encoding' => 'UTF-8'
    ));
    $this->addOption('configuration', array());
    $this->addOption('raise_error_if_invalid', true);
  }

  protected function doClean($value)
  {

    // autoload HTMLPutifier if required
    if (!class_exists('HTMLPurifier_PropertyList'))
    {
      if(!defined('HTMLPURIFIER_PREFIX'))
      {
        $path = sfConfig::get(
          'app_swToolbox_form_html_purifier_path',
          sfConfig::get('sf_plugins_dir').'/sfXssSafePlugin/lib/vendor/HTMLPurifier'
        );
        
        define('HTMLPURIFIER_PREFIX', $path);
      }
      
      require_once(HTMLPURIFIER_PREFIX . '/HTMLPurifier.includes.php');
    }

    $purifier = HTMLPurifier::instance();

    $config = HTMLPurifier_Config::create(array_merge(
      $this->getOption('core_configuration'),
      $this->getOption('configuration')
    ));

    $clean = $purifier->purify($value, $config);

    if($value != $clean && $this->getOption('raise_error_if_invalid'))
    {
      throw new sfValidatorError($this, 'bad_input');
    }

    // always return the purifier value
    return $clean;
  }
}
