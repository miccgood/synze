<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mode extends SpotOn {

    function __construct() {
        parent::__construct();
    }
    
    function setMode($mode){
        $this->session->set_userdata("mode", $mode);
        return json_encode(array("success" => true));
    }
}