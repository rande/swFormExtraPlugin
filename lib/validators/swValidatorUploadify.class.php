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
 * SVN : $Id$
 **/
class swValidatorUploadify extends sfValidatorFile
{

  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addMessage('temp_dir', 'Temporary directory does not exist');
    $this->addMessage('wrong_data', 'Unable to load downloaded files information');
  }
  /**
   * @param  $value the token
   * @return void
   */
  public function doClean($token)
  {

    $dir = sys_get_temp_dir().'/uploadify/'.$token;

    if(!is_dir($dir))
    {
      throw new sfValidatorError($this, 'temp_dir');
    }

    $state_file = $dir.'/files.json';
    if(!is_readable($state_file))
    {
      // no file uploaded ok ...
      return array();
    }

    $datas = json_decode(@file_get_contents($state_file), true);
    if(!is_array($datas))
    {
      throw new sfValidatorError($this, 'temp_dir');
    }

    $validated_files = array();
    foreach($datas as $data)
    {
      $validated_files[] = parent::doClean($data);
    }

    return $validated_files;
  }

  protected function isEmpty($value)
  {
    return in_array($value, array(null, '', array()), true);
  }
}