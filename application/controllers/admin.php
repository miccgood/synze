<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends SpotOn {
    function __construct() {
        $this->autoSetDefaultValue = TRUE;
        parent::__construct();
    }
    
    function index() {
        
        $this->crud->set_table('mst_user')
        ->set_subject('User')
        ->display_as("user_displayname" , "Name")
        ->display_as("permission_ID" , "Page")
        ->set_relation('user_type_ID', 'mst_user_type', 'user_type_name')
        ->set_relation_n_n('permission_ID', 'trn_permission', 'mst_permission', 'user_ID', 'permission_ID', 'page_name','seq')
        ->where("cpn_ID" , $this->cpnId)
        ->columns('user_displayname', 'user_type_ID', 'permission_ID')
        ->fields('user_displayname', 'user_type_ID', 'permission_ID')
        ;
        $this->output();
        
    }
    
    public function _beforeDelete($primary_key) {
        return $this->m->checkDeleteTerminal($primary_key);
    }
    
    
}