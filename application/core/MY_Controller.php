<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SpotOn extends CI_Controller {

    protected $indexArray = array();
    protected $cpnId = null;
    protected $userId = null;
    
    protected $autoSetDefaultValue = false;
    function __construct() {
        parent::__construct();

        /* Standard Libraries of codeigniter are required */
        $this->load->database();
        $this->load->helper('url');
        
        /* ------------------ */

        $this->load->library('grocery_crud');
        $this->load->library('stringutils', FALSE);
        $this->load->library('session');
        
        
        
        $this->load->model('spot_on_model', 'm');
        
        $this->crud = new Grocery_CRUD();
        $this->cpnId = $this->session->userdata("cpnID");
        $this->userId = $this->session->userdata("userID");
        
        
        
        if($this->nullToZero($this->cpnId) == 0 || $this->nullToZero($this->userId) == 0){
            if($this->cpnId !== false){
                $this->session->set_userdata("message_login", "Company Id Wrong");
            }
            redirect("login/logout");
        }else{
            $this->m->setCpnId($this->cpnId);
        }
        
        
        $this->setDefaultAction();
    }

    protected function output() {
        $output = $this->crud->render();
        $this->load->view('main.php', $output);
    }

    protected function setDefaultAction(){
        
        
        $this->crud
            ->set_theme('datatables')
            ->field_type("cpn_ID", "hidden", $this->cpnId) 
            ->field_type("page", "hidden", $this->router->class)
            ->field_type("create_date", "hidden")
            ->field_type("create_by", "hidden")    
            ->field_type("update_date", "hidden")    
            ->field_type("update_by", "hidden")    
//            ->setDefaultViewsPath("application/views")
            ->unset_print()->unset_export()->unset_read()
            ->callback_before_insert(array($this,'clearBeforeInsertAndUpdate'))
            ->callback_before_update(array($this,'clearBeforeInsertAndUpdate'))
            ->callback_before_delete(array($this,'_beforeDelete'));
            ;
    }
    
    protected function setFancyBox(){
        $this->crud->set_css("assets/fancyapps/source/jquery.fancybox.css?v=2.1.5");
        $this->crud->set_js("assets/fancyapps/source/jquery.fancybox.js?v=2.1.5");
    }
    
    public function clearBeforeInsertAndUpdate($array) {
        unset($array['page']);
        foreach ($this->indexArray as $key => $value) {
            unset($array[$value]);
        }
        
        
        if($this->autoSetDefaultValue){
            $array = $this->setDefaultValue($array);
        }
        return $array;
    }
    
    protected function getPkFormReq($index) {
        $lyt_id = $this->input->get($index);
        $session_lyt_id = $this->session->userdata($index);
        if($lyt_id != null){
            return $lyt_id;
        }else if($session_lyt_id != null){
            return $session_lyt_id;
        }
        return false ;
        
    }
    
    protected function nullToZero($param , $ret = "0") {
        return ($param === FALSE || $param === NULL || $param === "" ? $ret : $param);
    }
    
    protected function setDefaultValue($array, $mode = null) {
        
        $state = ($mode != null ? $mode : $this->crud->getState());
        
        switch ($state) {
            case "insert":
                $array["create_date"] = date("YmdHis", time());
                $array["create_by"] = $this->userId;
            case "update":
                $array["update_date"] = date("YmdHis", time());
                $array["update_by"] = $this->userId;
                break;
            default:
                
                break;
        }
        return $array;
    }
    
    
    
    protected function getMediaPath($file_name){
        //ดึง Path File จาก config 
        $path = $this->media['text_path'];

        //path ไฟล์จริงใน server
        return trim($path, "/")."/".$file_name;
    }
    
    protected function subString($string, $charFirst) {
        return substr($string, strpos($string, $charFirst));
    }
    
    public function _beforeDelete($primary_key)
    {
        $pk = $primary_key;
        return false;
    }
    
    public function getValueFromObj($obj, $attr){
        if($obj != null){
            if(property_exists($obj, $attr)){
                return $obj->$attr;
            }
            return NULL;
        }
        return NULL;
    }
    
    public function getValueFromArray($obj, $attr){
        if($obj != null){
            if(array_key_exists($attr, $obj)){
                return $obj[$attr];
            }
            return NULL;
        }
        return NULL;
    }
    
    
    public function getMode(){
        
        $mode = $this->session->userdata("mode");
        if($this->nullToZero($mode) == "0"){
            $mode = "A";
            //$ret = "A";//Advance Mode
            //$ret = "L";//Lite Mode
        }
        return $mode;
    }
}









class SpotOnLov extends SpotOn {
    protected function output() {
        $this->crud->unset_edit()->unset_back_to_list()->unset_list();
        $output = $this->crud->render();
        $this->load->view('lov.php', $output);
    }
}


class SpotOnReport extends SpotOn {
    
    protected function setDefaultAction(){
        
        
        $this->crud
            ->set_theme('datatables')
            ->field_type("cpn_ID", "hidden", $this->cpnId) 
            ->field_type("page", "hidden", $this->router->class)
            ->field_type("create_date", "hidden")
            ->field_type("create_by", "hidden")    
            ->field_type("update_date", "hidden")    
            ->field_type("update_by", "hidden")    
//            ->setDefaultViewsPath("application/views")
            ->unset_add()->unset_read()->unset_edit()->unset_delete()->unset_export()->unset_print()
            ->callback_before_insert(array($this,'clearBeforeInsertAndUpdate'))
            ->callback_before_update(array($this,'clearBeforeInsertAndUpdate'))
            ->callback_before_delete(array($this,'_beforeDelete'));
            ;
    }
}
