<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormTextareaMarkItUp represents a markItUp widget.
 *
 *
 * @package    symfony
 * @subpackage widget
 * @author     
 * @version    
 */
class swWidgetFormTextareaMarkItUpBbcode extends swWidgetFormTextareaMarkItUp
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * settings:  The markItUp settings
   *  * set: The markItUp set
   *  * skin: The markItUp skin
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('settings', array(
      'nameSpace' => 'bbcode',
      'markupSet' => array(
        array('name' => 'Bold', 'key' => 'B', 'openWith' => '[b]', 'closeWith' => '[/b]' ),
        array('name' => 'Italic', 'key' => 'I', 'openWith' => '[i]', 'closeWith' => '[/i]' ),
        array('name' => 'Underline', 'key' => 'U', 'openWith' => '[u]', 'closeWith' => '[/u]' ),
        array('separator' => '---------------'),
        array('name' => 'Quote', 'key' => 'Q', 'openWith'=> '\n[cite]\n', 'closeWith' => '\n[/cite]\n'),
      ),
      'resizeHandle' => false,
    ));
    
    $this->addOption('set_name', 'bbcode');
    $this->addOption('skin_name', 'simple');
  }
}
