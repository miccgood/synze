<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Scheduling extends SpotOn {

    function __construct() {
        parent::__construct();
        $this->autoSetDefaultValue = TRUE;
        $this->indexArray = array("player_group");
    }
    
    function clearBeforeInsertAndUpdate($post_data){
        $this->scheduling = $post_data;
        $post_data = parent::clearBeforeInsertAndUpdate($post_data);
        return $post_data;
    }
     
             
    
    public function index() {
        $this->crud->set_table('mst_shd')
                
        ->where("mst_shd.cpn_ID" , $this->cpnId)
        ->set_relation('story_ID', 'mst_story', 'story_name')
        ->set_subject('Scheduling')
        ->columns("shd_name","story_ID", "shd_start_date", "shd_start_time")
                
        ->fields("shd_ID", "shd_name","shd_desc", "story_ID", "shd_start_date", "shd_start_time", "player_group" ,
                'cpn_ID',
                "create_date", "create_by", "update_date", "update_by")
        
                ->display_as('shd_name', 'Name')
                ->display_as('shd_desc', 'Desc')
                ->display_as('story_ID', 'Story Name')
                ->display_as('shd_start_time', 'Start Time')
                ->display_as('shd_stop_time', 'Stop Time')
                ->display_as('shd_start_date', 'Effective Date')
                ->display_as('shd_stop_date', 'Expire Date')
                ->display_as('tmn_grp_ID', 'Terminal Group')
                ->display_as('player_group', 'Player Group')
                
                
                ->callback_field("player_group", array($this, "_player_group"))
                ->field_type('shd_ID', 'hidden')
                
                ->display_as('shd_start_time', 'Start Time')
        ->callback_after_insert(array($this,'afterInsert'))
        ->callback_after_update(array($this,'afterInsert'))
        ;
        $this->output();
    }
    
    function _player_group($value = '', $shd_ID = null, $row = "", $roe= ""){
        $result = $this->m->getTerminalGroup();
        $resultSelected = $this->m->getTerminalGroupByShdId($shd_ID);
        $selectedArray = array();
        foreach ($resultSelected as $value) {
            $selectedArray[$value->tmn_grp_ID] = $value->tmn_grp_ID;
        }
        $ret = "<select id='playerGroup' name='player_group[]' multiple='multiple' style='width:500px; display:none;'> " ;
            foreach($result as $value) {
                $selected = (in_array($value->tmn_grp_ID, $selectedArray) ? "selected='selected'" : "");            
                $ret .= "<option value='$value->tmn_grp_ID' $selected> $value->tmn_grp_name </option> ";
            }
        $ret .= "</select>";
        return $ret;
    }
    
    function callbackSchedulingName($value = '', $primary_key = null, $row = "")
    {
        return '<a href="'.site_url('schedulingitem?shd_id='.$primary_key->shd_ID).'">'.$value.'</a>';
    }
    
    function afterInsert($value = "", $pk =""){
        $shdId = $this->scheduling['shd_ID'];  
        $playerGroup = $this->scheduling['player_group'];
         $this->m->updateDeploymentByShdIdAndTmnGrp($pk, $playerGroup);
     }
     
    public function _beforeDelete($primary_key) {
        return true;//$this->m->checkDeleteScheduling($primary_key);
    }
     
}