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
 * @package    swFormExtraPlugin
 * @subpackage widgets
 * @author     Thomas Rabaix <thomas.rabaix@soleoweb.com>
 * @version    SVN: $Id$
 */
class swFormExtraPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    if($this->configuration instanceof sfApplicationConfiguration)
    {
      $this->dispatcher->connect('routing.load_configuration', array('swFormExtraRooting', 'listenToRoutingLoadConfigurationEvent'));
    }
  }
}