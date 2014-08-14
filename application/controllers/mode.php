<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mode extends SpotOn {

    function __construct() {
        parent::__construct();
    }
    
    function setMode($mode){
        $this->session->set_userdata("mode", $mode);
        return json_encode(array("success" => true));
    }
    
    function setCpn($cpnId){
        
        $superAdminCodeList = $this->configPermission["superAdminCodeList"];
        //ถ้าไม่ใช่ super admin ห้ามเข้า
        if(in_array($this->userTypeCode, $superAdminCodeList)){
            $this->cpnId = $cpnId;
            
            $this->m->setCpnId($this->cpnId);
            $this->session->set_userdata("cpnID", $this->cpnId);
            return json_encode(array("success" => true));
        } 
        return json_encode(array("success" => false));
        
    }
    
}