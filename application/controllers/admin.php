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
        ->display_as("cpn_ID" , "Company")
        ->display_as("user_type_ID" , "User Type")
        ->set_relation('user_type_ID', 'mst_user_type', 'user_type_name')
        ->set_relation('cpn_ID', 'mst_cpn', 'cpn_name')
        ->set_relation_n_n('permission_ID', 'trn_permission', 'mst_permission', 'user_ID', 'permission_ID', 'page_name','seq')
        ->where("cpn_ID" , $this->cpnId)
        ->columns('cpn_ID', 'user_displayname', 'user_type_ID', 'permission_ID')
        ->fields('cpn_ID', 'user_displayname', 'user_type_ID', 'permission_ID')
        ;
        $this->output();
        
    }
    
    protected function setDefaultAction(){
        
        
        $this->crud
            ->set_theme('datatables')
//            ->field_type("cpn_ID", "hidden", $this->cpnId) 
            ->field_type("page", "hidden", $this->router->class)
            ->field_type("permissionEdit", "invisible", $this->permissionEdit)
            ->field_type("create_date", "hidden")
            ->field_type("create_by", "hidden")    
            ->field_type("update_date", "hidden")    
            ->field_type("update_by", "hidden")    
//            ->setDefaultViewsPath("application/views")
            ->unset_print()->unset_export()->unset_read()
            ->callback_before_insert(array($this,'clearBeforeInsertAndUpdate'))
            ->callback_before_update(array($this,'clearBeforeInsertAndUpdate'))
            ->callback_before_delete(array($this,'_beforeDelete'))
            ->set_default_value(array("permissionEdit" => $this->permissionEdit))
            ;
            
            if(!$this->permissionEdit){
                $this->crud->unset_delete();
            }
            
    }
    
    public function _beforeDelete($primary_key) {
        return $this->m->checkDeleteTerminal($primary_key);
    }
    
    
}