<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Programmer extends SpotOn {

    function __construct() {
        parent::__construct();
    }
    
    public function alter() {
        $this->m->alter("ALTER TABLE `synze25561212`.`mst_pl` 
        ADD COLUMN `pl_type` ENUM('video','image','scrolling text') NULL DEFAULT 'video' AFTER `update_date`,
        ADD COLUMN `pl_expired` DATE NULL AFTER `pl_type`;

        ALTER TABLE `synze25561212`.`mst_media` 
        CHANGE COLUMN `media_type` `media_type` ENUM('video','image','scrolling text') NULL DEFAULT 'video' ;");
    }
    
    public function index() {
        
//        $this->genLog();
//        $this->genMedia();
//        $this->genPlaylist();
//        $this->genTerminal();
//        $this->genTerminalGroup();
//        $this->genLog();
    }
    
    private function genLog(){
        $terminalGroupResult = $this->m->getTerminalGroup();
        $terminalGroupArray = array();
        foreach ($terminalGroupResult as $value) {
            $terminalGroupArray[$value->tmn_grp_ID] = $value;
        }
        $terminalResult = $this->m->getTerminal();
        $terminalArray = array();
        foreach ($terminalResult as $value) {
            $terminalArray[$value->tmn_ID] = $value;
        }
        
        $playListResult = $this->m->getPlaylist();
        $playListArray = array();
        foreach ($playListResult as $value) {
            $playListArray[$value->pl_ID] = $value;
        }
        
        $mediaResult = $this->m->getMedia();
        $mediaArray = array();
        foreach ($mediaResult as $value) {
            $mediaArray[$value->media_ID] = $value;
        }
        
        $schedulingResult = $this->m->getScheduling();
        $schedulingArray = array();
        foreach ($schedulingResult as $value) {
            $schedulingArray[$value->shd_ID] = $value;
        }
        
        $storyResult = $this->m->getStory();
        $storyArray = array();
        foreach ($storyResult as $value) {
            $storyArray[$value->story_ID] = $value;
        }
        
        $layoutResult = $this->m->getLayout();
        $layoutArray = array();
        foreach ($layoutResult as $value) {
            $layoutArray[$value->lyt_ID] = $value;
        }
        
        $dspResult = $this->m->getDisplay();
        $dspArray = array();
        foreach ($dspResult as $value) {
            $dspArray[$value->dsp_ID] = $value;
        }
        
        
        for ($index = 0; $index < 10; $index++) {
            $arr = array();
//            mt_rand(5, 15);
//            
//            $rand_terminalGroup = array_rand($terminalGroupResult);
//            $terminalGroup = $terminalGroupResult[$rand_terminalGroup] ;
            
            $rand_terminal = array_rand($terminalArray);
//            if(!array_key_exists($rand_terminal, $terminalArray)){
//                continue;
//            }
            $terminal = $terminalArray[$rand_terminal] ;
            $tmnGrpId = $terminal->tmn_grp_ID;
            
            
            if(!array_key_exists($tmnGrpId, $terminalGroupArray)){
                continue;
            }
                
                
            $terminalGroup = $terminalGroupArray[$tmnGrpId];
            
            $rand_scheduling = array_rand($schedulingArray);
            $scheduling = $schedulingArray[$rand_scheduling];
            $shdId = $scheduling->shd_ID;
            
            $storyId =  $scheduling->story_ID;
            $story = $storyArray[$storyId];
            
            $storyItemList = $this->m->getStoryItemByStoryId($storyId);
            
            foreach ($storyItemList as $storyItem) {
                
                $dspID = $storyItem->dsp_ID;
                if(!array_key_exists($dspID, $dspArray)){
                    continue;
                }
                $display = $dspArray[$dspID];
                
                $lytID = $display->lyt_ID;
                if(!array_key_exists($lytID, $layoutArray)){
                    continue;
                }
                $layout = $layoutArray[$lytID];
                
                
                $plId = $storyItem->pl_ID;
                if(!array_key_exists($plId, $playListArray)){
                    continue;
                }
                $playList = $playListArray[$plId];
             
                        
                $playlistItemList = $this->m->selectTrnPlHasMediaByPlId($plId);
                
                $startDate = $this->randomDate($scheduling->shd_start_date, $scheduling->shd_start_date);
                $startTime = date('H:i:s', strtotime($startDate));
                foreach ($playlistItemList as $playlistItem) {
                    $mediaId = $playlistItem->media_ID;
                    if(!array_key_exists($mediaId, $mediaArray)){
                        continue;
                    }
                    $media = $mediaArray[$mediaId];
                    
                    $arr["tmn_grp_ID"] = $tmnGrpId;
                    $arr["tmn_grp_name"] = $terminalGroup->tmn_grp_name;
                    $arr["tmn_ID"] = $terminal->tmn_ID;
                    $arr["tmn_name"] = $terminal->tmn_name;
                    $arr["dpm_ID"] = 0;
                    $arr["shd_ID"] = $shdId;
                    $arr["shd_name"] = $scheduling->shd_name;
                    $arr["shd_start_date"] = $scheduling->shd_start_date;
                    $arr["shd_start_time"] = $scheduling->shd_start_time;
                    $arr["shd_stop_date"] = $scheduling->shd_stop_date;
                    $arr["shd_stop_time"] = $scheduling->shd_stop_time;
                    $arr["story_ID"] = $storyId;
                    $arr["story_name"] = $story->story_name;
                    $arr["lyt_ID"] = $lytID;
                    $arr["lyt_name"] = $this->getData($layout, "lyt_name");
                    $arr["dsp_ID"] = $dspID;
                    $arr["dsp_name"] = $display->dsp_name;
                    $arr["pl_ID"] = $plId;
                    $arr["pl_name"] = $playList->pl_name;
                    $arr["media_ID"] = $mediaId;
                    $arr["media_name"] = $media->media_name;
                    
                    
                    
                    $arr["start_date"] = date("YmdHis", strtotime($startDate));
                    $arr["start_time"] = date("His", strtotime($startTime));
                    $stopTime = strtotime($startTime) + $media->media_lenght;
                    
                    $arr["stop_date"] = date("YmdHis", strtotime($startDate));
                    $arr["stop_time"] = date("His", $stopTime);
                    $arr["cpn_ID"] = 1;

                    $arr["create_date"] = date("YmdHis", time());
                    $arr["create_by"] = $this->userId;
                    $arr["update_date"] = date("YmdHis", time());
                    $arr["update_by"] = $this->userId;
                
                    $arr["month"] = date("m", strtotime($startDate));
                    $arr["year"] = date("Y", strtotime($startDate));
                    
                    $this->m->insertLog($arr);
                    
                }
//                $int= mt_rand(1262055681,1262055681);
//                $string = date("Y-m-d H:i:s",$int);
            }
            
            
            
            
            
            
            
            
//            $this->m->insertLog($arr);
            
        }
//        
    }
    
    function getData($obj, $attr){
        if($obj != null){
            return $obj->$attr;
        }
    }
    
    function randomDate($start_date, $end_date)
    {
        // Convert to timetamps
        $min = strtotime($start_date);
        $max = strtotime($end_date);

        // Generate random number using above bounds
        $val = rand($min, $max);

        // Convert back to desired date format
        return date('Y-m-d H:i:s', $val);
    }

    private function genTerminalGroup(){
        $terminalGroup = $this->m->getTerminalGroup();
        
        foreach ($terminalGroup as $value) {
            $id = $value->tmn_grp_ID;
            $name = $value->tmn_grp_name;
            $type = "tmn_grp";
            $is_cancel = "N";
            $arr = array(
                "item_ID" => $id,
                "item_name" => $name,
                "item_type" => $type,
                "is_cancel" => $is_cancel
                );

            $this->m->insertLogItem($arr);
        }
    }
    
    private function genTerminal(){
        $terminal = $this->m->getTerminal();
        
        foreach ($terminal as $value) {
            $id = $value->tmn_ID;
            $name = $value->tmn_name;
            $type = "tmn";
            $is_cancel = "N";
            $arr = array(
                "item_ID" => $id,
                "item_name" => $name,
                "item_type" => $type,
                "is_cancel" => $is_cancel
                );

            $this->m->insertLogItem($arr);
        }
    }
    
    private function genPlaylist(){
        $playList = $this->m->getPlaylist();
        
        foreach ($playList as $value) {
            $id = $value->pl_ID;
            $name = $value->pl_name;
            $type = "pl";
            $is_cancel = "N";
            $arr = array(
                "item_ID" => $id,
                "item_name" => $name,
                "item_type" => $type,
                "is_cancel" => $is_cancel
                );

            $this->m->insertLogItem($arr);
        }
    }
    
    private function genMedia() {
        $media = $this->m->getMedia();
        
        foreach ($media as $value) {
            $id = $value->media_ID;
            $name = $value->media_name;
            $type = "m";
            $is_cancel = "N";
            $arr = array(
                "item_ID" => $id,
                "item_name" => $name,
                "item_type" => $type,
                "is_cancel" => $is_cancel
                );

            $this->m->insertLogItem($arr);
        }
    }
    
}