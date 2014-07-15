<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SpotOn extends CI_Controller {

    protected $indexArray = array();
    protected $cpnId = null;
    protected $userId = null;
    
    protected $autoSetDefaultValue = false;
    
    protected $isReadonly = false;
    
    
    
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
        
        $this->config->load('permission', true);
        $this->configPermission = $this->config->item('permission');
        
        $this->init();
        
        $this->session->set_userdata("userTypeCode", $this->userTypeCode);
                
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

    
    private function init(){
        $this->crud = new Grocery_CRUD();
        $this->cpnId = $this->session->userdata("cpnID");
        $this->userId = $this->session->userdata("userID");
        $this->permission = $this->session->userdata("permission");
        $this->userTypeCode = $this->session->userdata("userTypeCode");
        $this->permissionView = true;
                
        $this->userTypeCode = $this->m->getUserTypeCodeByUserId($this->userId);
        
        $this->permissionEdit = $this->getPermission();
    }
    
    private function getPermission(){
        
        $mapPermission = $this->configPermission["mapPermission"];
        
        $page = $this->router->class;
        
        $permissionEdit = false;
        if(array_key_exists($page, $mapPermission)){
            $page_code = $mapPermission[$page];
            
            //ดึง permission ใหม่ทุกครั้ง
            $this->permission = $this->m->getPermission($this->userId);
            if(array_key_exists($page_code, $this->permission)){
                $permissionEdit = true;
            } else {
                $this->crud->unset_add();
            }
        } else if($page == "admin"){
            $adminCodeList = $this->configPermission["adminCodeList"];
            //ถ้าไม่ใช่ admin ห้ามเข้า
            if(!in_array($this->userTypeCode, $adminCodeList)){
                $this->permissionView = FALSE;
            }
        }
        
        return $permissionEdit;
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
    
    public function getUserTypeCode(){
        return $this->userTypeCode;
    }
    
    public function getUserPermissionAdminPage(){
        $ret = FALSE;
        $adminCodeList = $this->configPermission["adminCodeList"];
        if(in_array($this->userTypeCode, $adminCodeList)){
            $ret = TRUE;
        }
        return $ret;
    }
    
    public function getPermissionEdit(){
        return $this->permissionEdit;
    }
      
    public function getPermissionView(){
        return $this->permissionView;
    }
    
    public function setPermissionView($permissionView){
        $this->permissionView = $permissionView;
    }
    
//    protected function setFancyBox(){
//        $this->crud->set_css("assets/fancyapps/source/jquery.fancybox.css?v=2.1.5");
//        $this->crud->set_js("assets/fancyapps/source/jquery.fancybox.js?v=2.1.5");
//    }
    
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
    
    
    
    protected function getMediaPath($file_name, $type = "text"){
        //ดึง Path File จาก config 
        $path = "";//$this->media['text_path'];
        
        if($type == "text"){
            $path = $this->media['text_path'];
        } else {
            $path = $this->media['media_path'];
        }
        
        
        //media_path

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
    
    
    public function getDisplayName(){
        
//        $ret 
        $displayName = $this->session->userdata("displayName");
//        $cpnName = $this->session->userdata("cpnName");
                
//        if(isset($displayName) && isset($cpnName)){
           
//        }
        return $displayName;
    }
    
    public function getCpnName(){
        
//        $ret 
//        $displayName = $this->session->userdata("displayName");
        $cpnName = $this->session->userdata("cpnName");
                
//        if(isset($displayName) && isset($cpnName)){
           
//        }
        return $cpnName;
    }
    
    protected function getStringFormDuration($sec){
        //3600;
//        $h = floor($sec / 3600);
//        $sec = $sec - $h * 3600
//        $m = floor($sec / 3600);
//        $h = $sec / 3600;
//        $h = $sec / 3600;
//        if(is_numeric($sec)){
//            $sec = $sec / 1000;
//        }
        $ret = "";
        foreach(array(3600=>':',60=>':',1=>'') as $p=>$suffix){

            if ($sec >= $p){

                $sec -= $d = $sec-$sec % $p;

                $temp = $d/$p;
                
                if(strlen($temp) === 0){
                    $temp = "00";
                }else if(strlen($temp) === 1){
                    $temp = "0". $temp;
                }
                $ret .= $temp."$suffix"; 

            } else {
                $ret .= "00"."$suffix";
            }

        }
        return $ret;
    }
    
    protected function getDurationFormString($string){
        //3600;
//        $h = floor($sec / 3600);
//        $sec = $sec - $h * 3600
//        $m = floor($sec / 3600);
//        $h = $sec / 3600;
//        $h = $sec / 3600;
//        $time = strtotime($string);
//        return $time;
//        if(strpos(":", $string) < 0){
//            return $string;
//        }
        $string = explode(":", $string );
        $count = count($string); 
        $ret = 0;
        if($count == 3){
            $ret = $string[0] * 3600 + $string[1] * 60 + $string[2];
        }else if($count == 2){
            $ret = $string[1] * 60 + $string[2];
        }else { 
            $ret = $string[0];
        }
        return $ret;
        
    }
    
    
    public function isReadonly(){
        return $this->isReadonly;
    }
    
    
    public function isThaiString($string){
//        $test = preg_match("^[ก-๙]+$",$string);
//        $test = ereg_replace("^[ก-๙]+$", "", $string);
//        $test = ereg("^[ก-๙]+$", $string );
//        $test = preg_replace('/[^ก-๙]/u','',$string);
//        
//        $test = preg_replace('/[^ก-๙]/u','',$string);
        
        $test = preg_replace('/[^ก-๙]/u','',$string);
        if( $test == "" ){ // หรือจะใช้ if( !ereg("^[\xA1-\xF9]+$",$value ) ){  ก็ได้นะครับ
            return false;
        } else {
            return true;
        }
    }
    
    public function getFileName($string){
        //ถ้ามีภาษาไทยปนอยู่
        $string = str_replace("." . pathinfo($string, PATHINFO_EXTENSION), "", $string);
                
        $isThaiString = $this->isThaiString($string);
        if($isThaiString){
            $stringTest = preg_replace('/[^ก-๙]/u','',$string);
            $string = str_replace($stringTest, "", $string);
            if($string == ""){
                $string = substr(md5($stringTest), 0, 10);
            }
            $string = str_replace(" ", "", $string);
        }
        
        return $string;
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
    
    protected $countPlayer = array();
    protected $countMedia = array();
    protected $sumDurationMedia = array();
    protected $sumDurationGroup = array();  
    
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
    
    protected function getData(){
        $_get = $this->input->get();
        
        $data["data"] = $this->m->getDataForReport($_get);
//        $this->countPlayerAndsumDuration($data);
        return $data;
    } 
    
    protected function countPlayerAndsumDuration($valuePrint) {
        
        foreach ($valuePrint["data"] as $value) {
            $arrMedia = $this->getValueFromObj($value, "media_ID");
//            $arrTmnGrp = $this->getValueFromObj($value, "tmn_grp_ID");
            $arrTmn = $this->getValueFromObj($value, "tmn_ID");
            $this->countPlayer[$arrTmn][$arrMedia] = $this->nullToZero($this->getValueFromArray($this->getValueFromArray($this->countPlayer, $arrTmn), $arrMedia), 0)  + 1;
            $this->countMedia[$arrMedia][$arrTmn] = $this->nullToZero($this->getValueFromArray($this->getValueFromArray($this->countMedia, $arrMedia), $arrTmn), 0) + 1;
            $duration = $this->getDurationFormString($value->duration);
            $this->sumDurationMedia[$arrMedia] = $this->nullToZero($this->getValueFromArray($this->sumDurationMedia, $arrMedia), 0) + $duration;
            $this->sumDurationGroup[$arrTmn] = $this->nullToZero($this->getValueFromArray($this->sumDurationGroup, $arrTmn), 0) + $duration;
        }
//        $valuePrint["countPlayer"] = 100;
//        $valuePrint["sumDuration"] = 123;
    }
    
    protected function getArray($input){
        return ($input == null) ? array() : $input;
    }
//    protected function phptopdf_html($html,$save_directory,$save_filename)
//    {		
//            $API_KEY = 'ayc6isbj8k6gswmfy';
//            $postdata = http_build_query(
//                    array(
//                            'html' => $html,
//                            'key' => $API_KEY
//                    )
//            );
//
//            $opts = array('http' =>
//                    array(
//                            'method'  => 'POST',
//                            'header'  => 'Content-type: application/x-www-form-urlencoded',				
//                            'content' => $postdata
//                    )
//            );
//
//            $context  = stream_context_create($opts);
//
//
//            $resultsXml = file_get_contents('http://phptopdf.com/htmltopdf.php', false, $context);
//            file_put_contents($save_directory.$save_filename,$resultsXml);
//    }
        
}
