<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends SpotOn {
    
    public static $SUPER_ADMIN_CODE = "01";
    public static $ADMIN_CODE = "02";
    public static $USER = "03";
    
    function __construct() {
        $this->autoSetDefaultValue = TRUE;
        parent::__construct();
    }
    
    function index() {
        
        $where = $this->getMainWhere();
        $whereCpn = $this->getWhereCpn();
        $whereUser = $this->getWhereUser();
        
        $state = $this->crud->getState();
        
        $this->crud->set_table('mst_user')
        ->set_subject('User')
        ->display_as("user_displayname" , "Display Name")
        ->display_as("permission_ID" , "Page")
        ->display_as("cpn_ID" , "Company")
        ->display_as("user_type_ID" , "User Type")
        ->set_relation('user_type_ID', 'mst_user_type', 'user_type_name', $whereUser, "user_type_code")
        ->set_relation('cpn_ID', 'mst_cpn', 'cpn_name', $whereCpn, "cpn_name")
        ->set_relation_n_n('permission_ID', 'trn_permission', 'mst_permission', 'user_ID', 'permission_ID', 'page_name','seq')
        ->where($where)
        ->columns('cpn_ID', 'user_displayname', 'user_type_ID', 'permission_ID')
        ->fields('cpn_ID', 'user_displayname', 'user_type_ID', 'permission_ID')
        ;
        $this->output();
        
    }
    
    private function getMainWhere(){
        $userTypeCode = $this->getUserTypeCode();
        
        $where = array();
        
        switch ($userTypeCode) {
            case Admin::$SUPER_ADMIN_CODE:
                //ถ้าเป็น super admin ให้เห็นได้ทุกอย่าง
//                $where = array();
                break;
            case Admin::$ADMIN_CODE:
                //ถ้าเป็น admin ให้เห็นแค่ cpn ของตัวเอง และ ไม่เห็น SUPER_ADMIN
                $where = array("mst_user.cpn_ID" => $this->cpnId, 
                    "user_type_code <> " => Admin::$SUPER_ADMIN_CODE,
                    "user_ID <> " => $this->userId);
                break;
            default:
                break;
        }
        
        return $where;
    }
    
    private function getWhereCpn(){
        $userTypeCode = $this->getUserTypeCode();
        
        $where = array();
        
        switch ($userTypeCode) {
            case Admin::$SUPER_ADMIN_CODE:
                //ถ้าเป็น super admin ให้เห็นได้ทุกอย่าง
//                $where = array();
                break;
            case Admin::$ADMIN_CODE:
                //ถ้าเป็น admin ให้เห็นแค่ cpn ของตัวเอง
                $where = array("cpn_ID" => $this->cpnId);
                break;
            default:
                break;
        }
        
        return $where;
    }
    
    private function getWhereUser(){
        $userTypeCode = $this->getUserTypeCode();
        
        $where = array();
        
        switch ($userTypeCode) {
            case Admin::$SUPER_ADMIN_CODE:
                //ถ้าเป็น super admin ให้เห็นได้ทุกอย่าง
//                $where = array();
                break;
            case Admin::$ADMIN_CODE:
                //ถ้าเป็น admin ให้เห็นแค่ cpn ของตัวเอง
                $where = array("user_type_code <> " => Admin::$SUPER_ADMIN_CODE);
                break;
            default:
                break;
        }
        
        return $where;
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
            
//            if(!$this->permissionEdit){
//                $this->crud->unset_delete();
//            }
            
    }
    public function clearBeforeInsertAndUpdate($array) {
        
        $permissionMap = 
                array(
                    Admin::$SUPER_ADMIN_CODE => array(Admin::$SUPER_ADMIN_CODE, Admin::$ADMIN_CODE, Admin::$USER),
                    Admin::$ADMIN_CODE => array(Admin::$ADMIN_CODE, Admin::$USER),
                    Admin::$USER => array()
                );
        
        //ดึงข้อมูลขึ้นมาว่าตอนนี้ userType เป็นอะไร
        $userTypeCodeDao = $this->m->getUserTypeCodeByUserId($this->userId);
        
        //ดึงสิทธืที่ user คนนี้ สามารถกำหนดให้ได้ออกมา
        $permissionArray = $permissionMap[$userTypeCodeDao];
        
        //ดึงสิทธืส่งเข้ามา
        $userTypeId = $array['user_type_ID'];
        
        
        $userTypeCodeXml = $this->m->getUserTypeCodeById($userTypeId);
        
        $isPermissionUpdate = false;
        if(in_array($userTypeCodeXml, $permissionArray)){
            $isPermissionUpdate = true;
        }
        
        
        if($isPermissionUpdate){
            parent::clearBeforeInsertAndUpdate($array);
        }
        
        
        return $isPermissionUpdate;
    }
    
}