<?php
/*
 * This file is part of the swFormExtraPlugin package.
 *
 * (c) 2008 Thomas Rabaix <thomas.rabaix@soleoweb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
class swFormExtraRooting
{
  public static function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $routing = $event->getSubject();

    if(!sfContext::hasInstance())
    {

      return;
    }
    
    $routing->appendRoute('sw_form_extra_bbcode_preview', new sfRoute('/sw-form-extra/bb-code-preview', array(
      'module' => 'swFormPreview',
      'action' => 'bbcodePreview',
    )));
  }
}