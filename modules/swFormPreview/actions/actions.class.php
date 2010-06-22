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
  public function executeBbcodePreview(sfRequest $request)
  {    
    $decoda = new swDecoda($request['data']);
    
    return $this->renderText($decoda->parse(true));
  }
}