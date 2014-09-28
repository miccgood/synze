<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class GroupPlayerScreen extends SpotOn {

    private $resolution ;
    function __construct() {
        parent::__construct();
        $this->autoSetDefaultValue = TRUE;
    }
    
    private function getTmnGrpId() {
        $index = 'tmn_grp_id';
        $tmn_grp_id = $this->input->get($index);
        $session_tmn_grp_id = $this->session->userdata($index);
        if($tmn_grp_id != null){
            return $tmn_grp_id;
        }else if($session_tmn_grp_id != null){
            return $session_tmn_grp_id;
        }
        return false ;
    }
            
    
    function ajax(){
        //array
        $post = $this->input->post();
        
        foreach ($post as $value) {
            $value["tmn_grp_ID"] = $this->getTmnGrpId();
            
            $value = $this->setDefaultValue($value);
//            if(strpos($value["tmn_ID"], "gen-") !== false){
//                $value["tmn_ID"] = null;
//                $this->m->insertDisplay($value);
//            }else{
                $this->m->updateScreen($value);
//            }
        }
        $ret = array("success" => true);
        return json_encode($ret);
    }
    function index() {
        
        $tmn_grp_id = $this->getTmnGrpId();
        $this->resolution = $this->m->getResolutionByGroupPlayerId($tmn_grp_id);
//        $crud = new grocery_CRUD();
        if(StringUtils::isStringNotBlankOrNull($tmn_grp_id)){
            $this->crud->where('tmn_grp_id', $tmn_grp_id);
            $this->crud->setBackUrl(site_url('groupplayer'));
            $this->session->set_userdata("tmn_grp_id", $tmn_grp_id);
        }
        
        $this->crud->set_table('mst_tmn')
            ->set_primary_key('tmn_ID', 'mst_tmn')
            ->order_by("tmn_name", "DESC")
//            ->set_relation('PlayList', 'trn_tmn_has_pl', 'tmn_ID', 'pl_ID', 'pl_name')
//            ->set_relation_n_n('PlayList', 'trn_tmn_has_pl', 'mst_pl', 'tmn_ID', 'pl_ID', 'pl_name')
            ->set_subject('Zone')
            ->columns('tmn_name', 'tmn_left', 'tmn_top', 'tmn_width', 'tmn_height')
            
            ->display_as('tmn_name', 'Name')
//            ->display_as('tmn_desc', 'Description')
            ->display_as('tmn_left', 'Left')
            ->display_as('tmn_top', 'Top')
            ->display_as('tmn_width', 'Width')
            ->display_as('tmn_height', 'Height')
//            ->display_as('tmn_zindex', 'ZIndex')
        ->fields('tmn_ID', 'tmn_name', 'tmn_left', 'tmn_top', 'tmn_width', 'tmn_height', 'tmn_grp_ID', 'cpn_ID',
                "create_date", "create_by", "update_date", "update_by")//, "PlayList"
        ->field_type("tmn_left", "integer")
        ->field_type("tmn_top", "integer")
        ->field_type("tmn_width", "integer")
        ->field_type("tmn_height", "integer")
//        ->field_type("tmn_zindex", "integer")
        ->field_type("tmn_grp_ID", "hidden", $tmn_grp_id)
        ->field_type("tmn_ID", "hidden")    
        ->unset_delete()
//             ->order_by('tmn_zindex','desc')   
         ;
      $this->crud->setCustomScript("var groupPlayerWidth = " . $this->nullToZero($this->resolution[0]->tmn_grp_width, 0) .";"
              . " var groupPlayerHeight = " . $this->nullToZero($this->resolution[0]->tmn_grp_height, 0) .";")
                ;
         
        $this->output();
    }
    
//    function groupplayer_callback($post_array) {
//        return $post_array;
//    }
    
    function setDefaultAction() {
        parent::setDefaultAction();
        $this->crud->unset_edit();
    }
    
    public function _beforeDelete($primary_key) {
        return $this->m->checkDeleteDisplay($primary_key);
    }
    
}
