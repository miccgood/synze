<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Deployment extends SpotOn {

    function __construct() {
        parent::__construct();
        $this->indexArray = array("shd_name");
    }
    
    function clearBeforeInsertAndUpdate($post_data){
        $this->post_data = $post_data;
        $post_data = parent::clearBeforeInsertAndUpdate($post_data);
        return $post_data;
    }
     
             
    
    public function index() {
//        $cat_id = $this->uri->segment(4);
        
        
//        $crud = new grocery_CRUD();
//        $crud->set_relation($field_name, $related_table, $related_title_field, $crud, $order_by)
//$crud->set_relation($field_name, $related_table, $related_title_field)
        $this->crud->set_table('mst_tmn_grp')
//        ->set_relation('story_ID', 'mst_story', 'story_name')
        ->set_relation_n_n('shd_name', 'trn_dpm', 'mst_shd', 'tmn_grp_ID', 'shd_ID', 'shd_name', 'shd_ID')
        ->set_relation_n_n('shd_start_date', 'trn_dpm', 'mst_shd', 'tmn_grp_ID', 'shd_ID', 'shd_start_date')
        ->set_relation_n_n('shd_start_time' , 'trn_dpm', 'mst_shd', 'tmn_grp_ID', 'shd_ID', 'shd_start_time')
        ->set_subject('Deployment')
        ->columns("tmn_grp_name", "shd_start_date", "shd_start_time")
        ->fields("tmn_grp_ID", "tmn_grp_name", "shd_name")
        
        ->callback_column('shd_start_date',array($this,'_callback_shd_start_date_time'))
        ->callback_column('shd_start_time',array($this,'_callback_shd_start_date_time'))
                ->display_as('tmn_grp_name', 'Scheduling')
                ->display_as('tmn_grp_name', 'Player Name')
                ->display_as('shd_start_date', 'Effective Time')
                ->display_as('shd_start_time', 'Begin Time')
                
                ->field_type("tmn_grp_ID", "hidden")
        ->callback_after_insert(array($this,'afterInsert'))
        ->callback_after_update(array($this,'afterInsert'))
        ;
        $this->output();
    }
    
    
    function _callback_shd_start_date_time($value, $row){
        return min(explode(",", $value));
    }
    function afterInsert($value = "", $row = "" , $a ="", $b =""){
        $tmnGrpId = $this->post_data["tmn_grp_ID"];
        $shdNameArr = $this->post_data['shd_name'];
        $this->m->updateDeploymentByShdIdAndTmnGrpId($tmnGrpId, $shdNameArr);
 }
     
}