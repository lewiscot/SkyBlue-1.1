<?php

/**
* @version        Beta 1.1 2008-07-29 11:50:00 $
* @package        SkyBlueCanvas
* @copyright    Copyright (C) 2005 - 2008 Scott Edwin Lewis. All rights reserved.
* @license        GNU/GPL, see COPYING.txt
* SkyBlueCanvas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
*/

defined('SKYBLUE') or die(basename(__FILE__));

class teaser extends manager {
    
    function __construct() {
        $this->Init();
    }
    
    function teaser() {
        $this->__construct();
    }

    function AddEventHandlers() {
        $this->AddEventHandler('OnBeforeSave','PrepareForSave');
    }
    
    function PrepareForSave() {
        $this->AddFieldValidation('name','notnull');
        $_POST['intro'] = $this->Encode($_POST['intro']);
    }
    
    function InitProps() {
        $this->SetProp('headings', array('Name', 'Tasks'));
        $this->SetProp(
            'tasks', 
            array(
                'up:up_arrow.gif', 
                'down:down_arrow.gif',
                TASK_SEPARATOR, 
                'edit', 
                'delete'
            )
        );
        $this->SetProp('cols', array('name'));
    }
    
    function InitEditor() {
        global $Core;
        
        // Set the form message
        
        $this->SetFormMessage('name', 'Teaser');
        
        // Initialize the object properties to empty strings or
        // the properties of the object being edited
        
        $_OBJ = $this->InitObjProps($this->skin, $this->obj);
        
        // This step creates a $form array to pass to buildForm().
        // buildForm() merges the $obj properites with the form HTML.
        
        $form['ID']    = $this->GetItemID($_OBJ);
        $form['NAME']  = $this->GetObjProp($_OBJ,'name');
        $form['INTRO'] = $this->GetTeaserText($_OBJ,'url');
        $form['LINK']  = $this->PageSelector($_OBJ);
        $form['ORDER'] = $Core->OrderSelector2(
            $this->objs, 'name', $_OBJ['name']
        );
        $this->BuildForm($form);
    }
    
    function GetTeaserText($obj) {
        if (isset($obj['intro']) && !empty($obj['intro'])) {
            return $this->Decode($obj['intro']);
        }
        return null;
    }
    
    function PageSelector($obj) {
        global $Core;
        
        $link = null;
        if (isset($obj['link']) && !empty($obj['link'])) {
            $link = $obj['link'];
        }
        
        $pages = $Core->xmlHandler->ParserMain(SB_PAGE_FILE);
        $opts = array();
        $opts[] = $Core->MakeOption('No Link', null);
        foreach ($pages as $p) {
            $s = $link == $p->id ? 1 : 0 ;
            $opts[] = $Core->MakeOption($p->name, $p->id, $s);
        }
        return $Core->SelectList($opts, 'link');
    }
}

?>