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
 * swWidgetFormUploadify represents an upload button which uses the project Uploadify
 *
 *  see http://www.uploadify.com/documentation/ for more information
 *
 * @package    swFormExtraPlugin
 * @subpackage widget
 * @author    Thomas Rabaix <thomas.rabaix@soleoweb.com>
 * @version    SVN: $Id$
 */
class swWidgetFormUploadify extends sfWidgetFormInput
{

  /**
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormInput
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->setOption('type', 'hidden');
    $this->addOption('options', array());
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if(!$value)
    {
      $value  = sha1(session_id().time().sfConfig::get('swToolbox_swFormExtra_hash'));
    }

    $id = $this->generateId($name).'_uploadify';

    $files = array();
    foreach($this->getUploadedFiles($value) as $data)
    {
      $files[] = $data['name'];
    }
    
    $html = parent::render($name, $value, $attributes, $errors);
    $html .= sprintf('<div id="%s"></div><div id="%s">%s</div>', $id, $id.'_current_files', implode(', ', $files));
    $html .= sw_form_extra_uploadify('#'.$id, $this->getOption('options'), $value);
    
    return $html;
  }

  /**
   * return the list of current downloaded files
   *
   * @param  $token
   * @return array
   */
  public function getUploadedFiles($token)
  {
    $file = sys_get_temp_dir().'/uploadify/'.$token.'/files.json';

    if(!is_readable($file))
    {
      // no file uploaded ok ...
      return array();
    }

    $data = json_decode(@file_get_contents($file), true);

    if(!$data)
    {
      return array();
    }

    return $data;
  }

}
