<?php

/**
* @version		Beta 1.1 2008-07-29 11:50:00 $
* @package		SkyBlueCanvas
* @copyright	Copyright (C) 2005 - 2008 Scott Edwin Lewis. All rights reserved.
* @license		GNU/GPL, see COPYING.txt
* SkyBlueCanvas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
*/

defined('SKYBLUE') or die(basename(__FILE__));

class snippets_model
{
    var $id           = null;
    var $name         = null;
    var $content      = null;
    var $cantarget    = 0;
    var $group        = 'collections';
    var $bundletype   = 'module';
    var $bundlesource = null;
    var $objtype      = null;
    var $loadas       = "xml";
    
    function __construct() 
    {
        ;
    }
    
    function snippets_model()
    {
        $this->__construct();
    }

}

?>