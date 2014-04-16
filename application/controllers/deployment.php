<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Deployment extends SpotOn {

    function __construct() {
        parent::__construct();
        $this->autoSetDefaultValue = TRUE;
        $this->indexArray = array("player_group", "dpm_ID");
    }
    
    function clearBeforeInsertAndUpdate($post_data){
        $this->scheduling = $post_data;
        $post_data = parent::clearBeforeInsertAndUpdate($post_data);
        return $post_data;
    }
     
    public function index() {
       $state = $this->crud->getState();
       if($state == "list" || $state == "delete"){
           $this->modeList();
       }else {
           $this->modeOther($state);
       }
    }
    
    public function afterInsert($value = "",  $pk = ""){
        $deployment = new stdClass();
        $dpmId = 0;
        $deployment->shd_ID = $pk;  
        $deployment->tmn_grp_ID = $this->scheduling['player_group'];
        $state = $this->crud->getState();
        if($state == "update"){
            $deployment->dpm_ID = $this->scheduling['dpm_ID'];  
            $dpmId = $this->m->updateDeploymentByDpmId($deployment);
        } else if($state == "insert") {
            $dpmId = $this->m->insertDeployment($deployment);
        }
        $this->session->set_userdata("edit_dpm_ID", $dpmId);
    }
    
    
     
     
     private function modeOther($state){
        
        $dpmId = $this->nullToZero($this->input->get("dpm_id"), $this->session->userdata("edit_dpm_ID"));
        $this->dmp = array();
        $tmnGrpId = 0;
        $storyID = 0;
        if($state == "edit"){
            $dmp = $this->m->getDeploymentById($dpmId);
            $this->dmp = $dmp[0];
            $tmnGrpId = $this->dmp->tmn_grp_ID;
            
            $shdId = $this->dmp->shd_ID;
            $shd = $this->m->getSchedulingById($shdId);
            $this->shd = $shd[0];
            $storyID = $this->shd->story_ID;
        }
        
        $terminalGroupList = $this->m->getTerminalGroup();
        $this->terminalGroup = array();
        foreach ($terminalGroupList as $terminalGroup) {
            $this->terminalGroup[$terminalGroup->tmn_grp_ID] = $terminalGroup->tmn_grp_name;
        }
        
        
        $storyList = $this->m->getStory();
        $this->story = array();
        foreach ($storyList as $story) {
            $this->story[$story->story_ID] = $story->story_name;
        }
        
        $this->crud->set_table('mst_shd')
                
        ->where("mst_shd.cpn_ID" , $this->cpnId)
//        ->set_relation('story_ID', 'mst_story', 'story_name')     
        ->set_subject('Scheduling')
        ->columns("shd_name","story_ID", "shd_start_date", "shd_start_time")
                
        ->fields("player_group", "shd_ID", "shd_name","shd_desc", "story_ID", "shd_start_date", "shd_start_time", "dpm_ID" ,
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
                
                
//                ->callback_field("player_group", array($this, "_player_group"))
                ->field_type('shd_ID', 'hidden')
                ->field_type('dpm_ID', 'hidden', $dpmId)
                ->field_type('player_group', 'dropdown', $this->terminalGroup, $tmnGrpId)
                ->field_type('story_ID', 'dropdown', $this->story, $storyID)
                ->display_as('shd_start_time', 'Start Time')
        ->callback_after_insert(array($this,'afterInsert'))
        ->callback_after_update(array($this,'afterInsert'))
        ;
        $this->output();
     }
     
     private function modeList(){
        $this->crud->set_table('trn_dpm')
                
            ->where("trn_dpm.cpn_ID" , $this->cpnId)
            ->set_relation('shd_ID', 'mst_shd', 'shd_name, shd_start_date, shd_start_time')
            ->set_relation('tmn_grp_ID', 'mst_tmn_grp', 'tmn_grp_ID, tmn_grp_name')

            ->set_subject('Deployment')
            ->columns("tmn_grp_ID", "shd_name", "shd_start_date", "shd_ID")

                    ->add_action('Edit', '', 'storyitem/index','ui-icon-pencil', array($this,'edit_participant'))
                    ->unset_edit()

                    ->display_as('shd_ID', 'Start Time')
                    ->display_as('shd_name', 'Scheduling Name')
                    ->display_as('shd_desc', 'Scheduling Desc')
                    ->display_as('story_ID', 'Story Name')
                    ->display_as('shd_start_time', 'Start Time')
                    ->display_as('shd_stop_time', 'Stop Time')
                    ->display_as('shd_start_date', 'Effective Date')
                    ->display_as('shd_stop_date', 'Expire Date')
                    ->display_as('tmn_grp_ID', 'Terminal Group')
                    ->display_as('player_group', 'Player Group')

            ;
            $this->output();
    }
    
    public function edit_participant($primary_key , $row)
    {
       return 'deployment/index/edit/'.$row->shd_ID.'?dpm_id=' . $primary_key;
    }
     
    public function _beforeDelete($primary_key) {
        return true;//$this->m->checkDeleteDeplayment($primary_key);
        
        
//        $ret = $this->m->checkDeleteStory($primary_key);
//        if($ret){
//            $this->m->deleteTrnDspHasPlByStoryId($primary_key);
//            return true;
//        } else {
//            return $ret;
//        }
        
    }
}