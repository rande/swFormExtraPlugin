<?php
/*
 * This file is part of the swFormExtraPlugin package.
 *
 * (c) 2008 Thomas Rabaix <thomas.rabaix@soleoweb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class swFormPreviewActions extends sfActions
{
  public function executeBbcodePreview(sfWebRequest $request)
  {    
    $decoda = new swDecoda($request['data']);
    
    return $this->renderText($decoda->parse(true));
  }

  public function executeUploadify(sfWebRequest $request)
  {
    $token = $request['token'];
    $dir = sys_get_temp_dir().'/uploadify/'.$token;

    // check token validity
    $this->forward404if(!preg_match("([0-9a-zA-z]*)", $token));
    $this->forward404if(!is_dir($dir));


    // ok token is valid and 
    $file_data = $request->getFiles('Filedata');

    $this->logMessage(print_r($file_data, 1));

    // move file
    if(!move_uploaded_file($file_data['tmp_name'], $dir.'/'.basename($file_data['tmp_name'])))
    {
      throw new sfException('unable to copy the file');
    }

    $this->logMessage('Copy '.$file_data['tmp_name'].' => '. $dir.'/'.basename($file_data['tmp_name']));

    // store file information into a json serialized file
    $json_file = $dir.'/files.json';
    $data = @file_get_contents($json_file);
    $data = $data ? json_decode($data, true) : array();

    $file_data['tmp_name'] = $dir.'/'.basename($file_data['tmp_name']);

    $data[] = $file_data;

    $this->logMessage("Saving states file : ". json_encode($data));
    
    file_put_contents($json_file, json_encode($data));

    return sfView::NONE;
  }
}