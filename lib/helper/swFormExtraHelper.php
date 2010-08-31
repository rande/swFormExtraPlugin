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
 * list of options : http://www.uploadify.com/documentation/
 *
 * @param  $selector
 * @param array $options
 * @return void
 */
function sw_form_extra_uploadify($selector, $options = array(), $token = false)
{

  if(!$token)
  {
    $token  = sha1(session_id().time().sfConfig::get('swToolbox_swFormExtra_hash'));
  }

  $dir = sys_get_temp_dir().'/uploadify/'.$token;
  if(!is_dir($dir) && !@mkdir($dir, 0700, true))
  {
    throw new sfException('Unable to create the destination folder : '.$dir);
  }
  
  $options['uploader']  = isset($options['uploader'])   ? $options['uploader']  : '/swFormExtraPlugin/js/uploadify/uploadify.swf';
  $options['script']    = isset($options['script'])     ? $options['script']    : url_for('sw_form_extra_uploadify', array(
    'token' => $token,
  ));
  $options['cancelImg'] = isset($options['cancelImg'])  ? $options['cancelImg'] : '/swFormExtraPlugin/js/uploadify/cancel.png';
  $options['auto']      = isset($options['auto'])       ? $options['auto']      : true;

  echo sprintf('
    <script type="text/javascript">
    // <![CDATA[
    jQuery(document).ready(function() {
      jQuery(\'%s\').uploadify(%s);
    });
    // ]]>
    </script>', $selector, json_encode($options)
  );
}