<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Display extends SpotOn {

    private $resolution ;
    function __construct() {
        parent::__construct();
        $this->autoSetDefaultValue = TRUE;
    }
    
    private function getLytId() {
        $index = 'lyt_id';
        $lyt_id = $this->input->get($index);
        $session_lyt_id = $this->session->userdata($index);
        if($lyt_id != null){
            return $lyt_id;
        }else if($session_lyt_id != null){
            return $session_lyt_id;
        }
        return false ;
    }
            
    
    function ajax(){
        //array
        $post = $this->input->post();
        
        foreach ($post as $value) {
            $value["lyt_ID"] = $this->getLytId();
            
            $value = $this->setDefaultValue($value);
            if(strpos($value["dsp_ID"], "gen-") !== false){
                $value["dsp_ID"] = null;
                $this->m->insertDisplay($value);
            }else{
                $this->m->updateDisplay($value);
            }
        }
        $ret = array("success" => true);
        return json_encode($ret);
    }
    function index() {
        
        $lyt_id = $this->getLytId();
        $this->resolution = $this->m->getResolutionByLayoutId($lyt_id);
//        $crud = new grocery_CRUD();
        if(StringUtils::isStringNotBlankOrNull($lyt_id)){
            $this->crud->where('lyt_id', $lyt_id);
            $this->crud->setBackUrl(site_url('layout'));
            $this->session->set_userdata("lyt_id",$lyt_id);
        }
        
        $this->crud->set_table('mst_dsp')
            ->set_primary_key('dsp_ID', 'mst_dsp')
            ->order_by("dsp_name", "DESC")
//            ->set_relation('PlayList', 'trn_dsp_has_pl', 'dsp_ID', 'pl_ID', 'pl_name')
//            ->set_relation_n_n('PlayList', 'trn_dsp_has_pl', 'mst_pl', 'dsp_ID', 'pl_ID', 'pl_name')
            ->set_subject('Display')
            ->columns('dsp_name', 'dsp_left', 'dsp_top', 'dsp_width', 'dsp_height', 'dsp_zindex')
            
            ->display_as('dsp_name', 'Name')
//            ->display_as('dsp_desc', 'Description')
            ->display_as('dsp_left', 'Left')
            ->display_as('dsp_top', 'Top')
            ->display_as('dsp_width', 'Width')
            ->display_as('dsp_height', 'Height')
            ->display_as('dsp_zindex', 'ZIndex')
        ->fields('dsp_ID', 'dsp_name', 'dsp_left', 'dsp_top', 'dsp_width', 'dsp_height', 'dsp_zindex', 'lyt_ID', 'cpn_ID',
                "create_date", "create_by", "update_date", "update_by")//, "PlayList"
        ->field_type("dsp_left", "integer")
        ->field_type("dsp_top", "integer")
        ->field_type("dsp_width", "integer")
        ->field_type("dsp_height", "integer")
        ->field_type("dsp_zindex", "integer")
        ->field_type("lyt_ID", "hidden", $lyt_id)
        ->field_type("dsp_ID", "hidden")        
             ->order_by('dsp_zindex','desc')   
         ;
      $this->crud->setCustomScript("var layoutWidth = " . $this->resolution[0]->lyt_width .";"
              . " var layoutHeight = " . $this->resolution[0]->lyt_height .";")
                ;
         
        $this->output();
    }
    
//    function layout_callback($post_array) {
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
