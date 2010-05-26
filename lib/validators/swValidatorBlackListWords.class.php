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
class swValidatorBlackListWords extends sfValidatorString
{
  
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('black_list_words', array());
    $this->addOption('black_list_files', array());

    $this->addMessage('black_listed_word', 'The field contains invalid word');
  }

  public function doClean($value)
  {
    $value = parent::doClean($value);

    $black_listes_words = $this->getBlackListedWords();
    $words = explode(' ', $value);

    $words = array_unique($words);
    $words = array_map(array($this, 'cleanWord'), $words);

    $diff = array_intersect($words, $black_listes_words);
  
    if(count($diff) > 0)
    {
        
      throw new sfValidatorError($this, 'black_listed_word', array('value' => $value));
    }
    
  }

  public function getBlackListedWords()
  {
    $words = $this->getOption('black_list_words');

    foreach($this->getOption('black_list_files') as $file)
    {
      if(!is_readable($file))
      {
        throw new RuntimeException('The file is not present');
      }

      $words = array_merge($words, file($file));
    }

    // clean up words
    $words = array_unique($words);
    $words = array_map(array($this, 'cleanWord'), $words);
    
    return $words;
  }

  public function cleanWord($word)
  {
    return strtolower(trim($word));
  }
}
