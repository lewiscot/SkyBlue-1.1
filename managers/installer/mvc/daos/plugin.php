<?php

/**
* @version    v1.1 2008-12-12 19:47:43 $
* @package    SkyBlueCanvas
* @copyright  Copyright (C) 2005 - 2008 Scott Edwin Lewis. All rights reserved.
* @license    GNU/GPL, see COPYING.txt
* SkyBlueCanvas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
*/

defined('SKYBLUE') or die(basename(__FILE__));

class PluginDAO {

    var $data;
    var $directory;

    function __construct() {
        $this->setDirectory(SB_SITE_DATA_DIR . "plugins/");
        if (!$this->exists(SB_SITE_DATA_DIR . "plugins/")) {
            FileSystem::make_dir(SB_SITE_DATA_DIR . "plugins/");
        }
    }

    function PluginDAO() {
        $this->__construct();
    }
    
    function setDirectory($directory) {
        $this->directory = $directory;
    }
    
    function save($package) {
    
        $Filter = new Filter;
        
        $name = $this->getName($Filter->get($package, 'name', null));
        
        if ($Filter->get($package, 'error', false)) {
            // An HTTP error occurred
            return false;
        }
        else if (empty($name)) {
            // An empty file name was posted
            return false;
        }
        else if ($this->exists($name)) {
            return false;
        }

        $ini = FileSystem::read_config(
            SB_MANAGERS_DIR . "installer/config.php"
        );
        $Uploader = new Uploader(
            isset($ini['mimes']) ? $ini['mimes'] : array(),
            array(SB_TMP_DIR)
        );
        
        list($result, $tmpfile) = $Uploader->upload($package, SB_TMP_DIR);
        
        if (intval($result) != 1) {
            // The file was not uploaded
            return false;
        }
        
        return $this->unzip($tmpfile, $name);
    }
    
    function getName($zip) {
        return str_replace(".zip", ".php", $zip);
    }
    
    function unzip($pkg, $plugin) {
        global $Core;

        if (!file_exists($pkg) || file_exists($plugin)) {
            return false;
        }
        $unzipOk = $Core->Unzip($pkg, $plugin);
        if (file_exists($plugin . "/$plugin")) {
            FileSystem::copy_file($plugin . "/$plugin", $this->directory . $plugin);
            FileSystem::delete_dir($plugin);
        }
        FileSystem::delete_file($pkg);
        return $unzipOk;
    }
        
    function delete($name) {
        if ($this->exists($name)) {
            return FileSystem::delete_dir("{$this->directory}{$name}/", false);
        }
        return false;
    }
    
    function index() {
        $data = FileSystem::list_files($this->directory, 0);
        $this->data = array();
        for ($i=0; $i<count($data); $i++) {
            array_push($this->data, basename($data[$i]));
        }
    }
    
    function getData() {
        return $this->data;
    }
    
    function exists($name) {
        return (!empty($name) && file_exists($this->directory . $name));
    }
    
}

?>