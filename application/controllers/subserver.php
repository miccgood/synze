<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SubServer extends SpotOn {
    function __construct() {
        $this->autoSetDefaultValue = TRUE;
        parent::__construct();
    }
    
    function index() {
        
        $this->crud->set_table('terminal')
        ->set_subject('Terminal')
        ->where("terminal.cpn_ID" , $this->cpnId)
        ->columns('terminal_name', 'terminal_type','tmn_desc')
//        ->fields("tmn_grp_ID", 'tmn_name', 'tmn_desc', 'tmn_uuid', 'tmn_monitor', 'cpn_ID',
//                "create_date", "create_by", "update_date", "update_by")
////            ->where("tmn_grp_ID", $tmnGrpId)
//            ->order_by("tmn_name", "DESC")
////            ->callback_column('cat_name',array($this,'callbackCatName'))
////            ->callback_column('count_media', array($this, 'countMedia'))
//            ->display_as('tmn_grp_ID', 'Group Name')
//            ->display_as('tmn_name', 'Name')
//            ->display_as('tmn_desc', 'Description')
//            ->display_as('tmn_os', 'OS')
//              
//            ->display_as('tmn_uuid', 'UUID')
//            ->display_as('tmn_regis_date', 'Register Date')
//                
//            ->display_as('tmn_status_id', 'Status')
//            ->display_as('tmn_status_message', 'Message')
//            ->display_as('tmn_status_update', 'Status Update')
//            ->display_as('tmn_monitor', 'Monitor')    
////                
//            ->field_type('tmn_grp_ID', 'dropdown', $this->terminalGroup , $terminalGroupId)
//            ->field_type('tmn_os', 'readonly')
////            ->field_type('tmn_uuid', 'readonly')
//            ->field_type('tmn_regis_date', 'readonly')
////            ->field_type('tmn_monitor', 'dropdown', array("0" => "inactive", "1" => "active"))
//            ->field_type('tmn_status_id', 'readonly')
//            ->field_type('tmn_status_message', 'readonly')
//            ->field_type('tmn_status_update', 'readonly')
////            ->field_type('tmn_grp_ID', 'hidden')
//                
//                
//            ->required_fields("tmn_grp_ID", "tmn_name", "tmn_monitor", 'tmn_uuid')
//            ->field_type('cpn_ID', 'hidden', $this->cpnId)
        
;
        $this->output();
        
    }
    
    public function _beforeDelete($primary_key) {
        return $this->m->checkDeleteTerminal($primary_key);
    }
    
    
}