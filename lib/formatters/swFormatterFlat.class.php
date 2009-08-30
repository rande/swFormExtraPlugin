<?php
/*
 * This file is part of the swFormExtraPlugin package.
 *
 * (c) 2008 Thomas Rabaix <thomas.rabaix@soleoweb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
class swFormatterFlat extends sfWidgetFormSchemaFormatter
{
  
  protected
    $rowFormat       = "%error%%label%\n  %field%%help%\n",
    $errorRowFormat  = "\n%errors%\n",
    $errorListFormatInARow     = "  %errors%\n",
    $errorRowFormatInARow      = "  %error%\n",
    $helpFormat      = '%help%',
    $decoratorFormat = "%content%";
  
}