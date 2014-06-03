<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class schedulingitem extends SpotOn {

    function __construct() {
        parent::__construct();
        $this->autoSetDefaultValue = true;
//        $this->config->load('story', true);
//        $this->layout = $this->config->item('story');
    }
    
    function clearBeforeInsertAndUpdate($post_data){
        $post_data = parent::clearBeforeInsertAndUpdate($post_data);
        $this->scheduling = $post_data;
        return $post_data;
    }
     
             
    
    public function index() {
        $shdId = $this->getPkFormReq("shd_id");
         
         
        $this->session->set_userdata("shd_id", $shdId);
//        $data = $this->getDefaultValue($shdId);
//        $playlistDao = $this->m->getPlaylistDropDownForStoryItem();
//        $playlistXml = json_encode($playlistDao);
//        
//        $data = $this->getDefaultValue($storyId, $layoutId);
        
        
        
        $this->crud->set_table('trn_dpm')
        ->set_relation('shd_ID', 'mst_shd', 'shd_name')
//        ->set_relation_n_n('tmn_grp_ID', 'trn_dpm', 'mst_tmn_grp', 'shd_ID', 'tmn_grp_ID', 'tmn_grp_name')
        ->set_subject('Scheduling')
        ->columns("shd_name","shd_desc", "shd_start_date", "shd_start_time")
        ->fields("shd_ID", "shd_name","shd_desc", "story_ID", "shd_start_date", "shd_start_time", "shd_stop_date", "shd_stop_time", 'tmn_grp_ID')
        ->callback_column('shd_ID',array($this,'callbackSchedulingName'))
        ->callback_column('shd_desc',array($this,'_callback_shd_desc'))
        ->callback_column('shd_start_date',array($this,'_callback_shd_start_date'))
        ->callback_column('shd_start_time',array($this,'_callback_shd_start_time'))
                
                ->display_as('shd_ID', 'Name')
                ->display_as('shd_name', 'Name')
                ->display_as('shd_desc', 'Desc')
                ->display_as('story_ID', 'Story Name')
                ->display_as('shd_start_time', 'Start Time')
                ->display_as('shd_stop_time', 'Stop Time')
                ->display_as('shd_start_date', 'Effective Date')
                ->display_as('shd_stop_date', 'Expire Date')
                ->display_as('tmn_grp_ID', 'Terminal Group')
                
                
                ->field_type('shd_ID', 'hidden')
                ->display_as('shd_start_time', 'dateTime')
        ->callback_after_insert(array($this,'afterInsert'))
        ->callback_after_update(array($this,'afterInsert'))
        ;
        $this->output();
    }
    
    function _callback_shd_desc($value = '', $primary_key = null, $row = "")
    {
        $dpmId = $primary_key->dpm_ID;
        $ret = $this->m->getShdDescByDpmId($dpmId);
        return $ret["0"]->VALUE;
    }
    
    function _callback_shd_start_date($value = '', $primary_key = null, $row = "")
    {
        $dpmId = $primary_key->dpm_ID;
        $ret = $this->m->getMinStartDateShdByDpmId($dpmId);
        return $ret["0"]->VALUE;
    }
    
    
    function _callback_shd_start_time($value = '', $primary_key = null, $row = "")
    {
        $dpmId = $primary_key->dpm_ID;
        $ret = $this->m->getMinStartTimeShdByDpmId($dpmId);
        return $ret["0"]->VALUE;
    }
    
//    function callbackSchedulingName($value = '', $primary_key = null, $row = "")
//    {
//        return '<a href="'.site_url('schedulingitem?shd_id='.$primary_key->shd_ID).'">'.$value.'</a>';
//    }
    
    function afterInsert(){
        $shdId = $this->scheduling['shd_ID'];
        $tmnGrp = $this->scheduling['tmn_grp_ID'];
         $this->m->updateDeploymentDateByShdIdAndTmnGrpId($shdId, $tmnGrp);
     }
     
//     private function getDefaultValue($shdId){
//        $data = array();
//        $data["story"] = $this->m->getStoryById($storyId);
//        $data["storyItem"] = $this->m->getStoryItemByStoryId($storyId);
//        $data["layoutAll"] = $this->m->getLayout();
//        $data["layout"] = $this->m->getLayoutById($layoutId);
//        $data["display"] = $this->m->getDisplayByLayoutId($layoutId);
//        
//        return $data;
//    }
     
}