<?php
/*
 * This file is part of the swFormExtraPlugin package.
 *
 * (c) 2008 Thomas Rabaix <thomas.rabaix@soleoweb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
include(dirname(__FILE__).'/../bootstrap/unit.php');

$t = new lime_test(3, new lime_output_color());

$black_list = array('salop');
$message    = "voici mon super message salop";

$v = new swValidatorBlackListWords(array('black_list_words' => $black_list));

try {
  $v->clean($message);
  $t->fail('::clean() fail to detect salop');
} catch(Exception $e) {
  $t->pass('::clean() detect bad words');
}


$v = new swValidatorBlackListWords(array(
  'black_list_words' => $black_list,
  'black_list_files' => array(
    '../../data/badword_fr.txt',
    '../../data/badword_en.txt',
  )
));

$message = 'dude, check this ass';
try {
  $v->clean($message);
  $t->fail('::clean() fail to detect bad word');
} catch(Exception $e) {
  $t->pass('::clean() detect bad words');
}


$v = new swValidatorBlackListWords(array(
  'black_list_words' => $black_list,
  'black_list_files' => array(
    '../../data/badword_fr.txt',
    '../../data/badword_en.txt',
  )
));

$message = 'salut voici un message valide';
try {
  $v->clean($message);
  $t->pass('::clean() message is valid');
} catch(Exception $e) {
  $t->pass('::clean() detect error in a valid message');
}
