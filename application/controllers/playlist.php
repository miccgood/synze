<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PlayList extends SpotOn {

    private $playlistName = "";
    function __construct() {
        parent::__construct();
        $this->indexArray = array("Media", "media_temp", "mediaTemp", "event", "autoCreateStory");
        $this->config->load('playlist', true);
        $this->playlist = $this->config->item('playlist');
    }
    
    public function getPlaylistType(){
        $event = $this->input->post("pl_type");
        if($event === FALSE || $event === ""){
            $event = "video";
        }
        return $event;
    }
    
    public function _clone($primary_key) {
       return 'playlist/fclone/add/'.$primary_key;
    }
    
    
    public function cloning() {
         //array
        $post = $this->input->post();
        $playlist = $post["playlist"];
        $playlist["pl_lenght"] = $this->getDuration($playlist["pl_lenght"]);
        
        $expireDate = $playlist["pl_expired"];
        $expireDate = str_replace('/', '-', $expireDate);

        $playlist["pl_expired"] = date("YmdHis", strtotime($expireDate));
        
        $playlist["create_date"] = date("YmdHis", time());
        $playlist["create_by"] = $this->userId;
        $playlist["update_date"] = date("YmdHis", time());
        $playlist["update_by"] = $this->userId;
        
        $playlistId = $this->m->insertPlaylist($this->setDefaultValue($playlist));
        
        $ret = array("success" => false);
        if($this->nullToZero($playlistId) != "0"){
            $this->m->insertPlaylistItem($playlistId, $post["media"]);
            
            if($this->getMode() == "L"){
                $this->autoCreateStory($playlistId, $playlist["pl_name"]);
            }
            
            $ret = array("success" => true, "pl_ID" => $playlistId);
        }
        
        
        echo json_encode($ret);
    }
    
    public function index() {
        $this->detail();
       
        $this->output();
    }
    
    function _pl_lenght($value = "" , $pk = "", $row = "" , $rows = "") {
        
        $string = explode(":", $this->getStringFormDuration($this->nullToZero($value)));
        
        $output = "<input name='lenght' class='numeric' id='hour' type='text' maxlength='11' value='$string[0]' style='width:30px; text-align: right;'/> : ";
        $output .= "<input name='lenght' class='numeric' id='min' type='text' maxlength='2' value='$string[1]' style='width:30px;'/> : ";
        $output .= "<input name='lenght' class='numeric' id='sec' type='text' maxlength='2' value='$string[2]' style='width:30px;'/>";
        $output .= "<input name='pl_lenght' class='numeric' id='field-pl_lenght' type='text' maxlength='11' value='$value' style='display:none;' /> ";
        return $output;
    }
     
    function _pl_usage($value = "" , $pk = "", $row = "" , $rows = "") {
        return "<div id='progressbar' style='display:none;'><div class='progress-label'>$value</div></div> <input type='hidden' name='pl_usage' id='field-pl_usage' value='$value'/>";
    }
    
    function _length($value = "" , $pk = "", $row = "" , $rows = "") {
        return $this->getStringFormDuration($this->nullToZero($value));
    }
     
    function _usage($value = "" , $pk = "", $row = "" , $rows = "") {
        return $this->getStringFormDuration($this->nullToZero($value));
    }
    
//    function _autoCreateStory($value = "" , $pk = "", $row = "" , $rows = "") {
//        if($row->crud_type == "hidden"){
//            return "";
//        }
//        return "<input type='checkbox' name='autoCreateStory' style='margin:5px;'/>";
//    }
    
    
    function clearBeforeInsertAndUpdate($post) {
//        $this->autoCreateStory = $post["autoCreateStory"];
//        $this->playlistName = $post["pl_name"];
        
//        $playlistResult = $this->m->getPlaylistById($playlistId);
        
        if($this->crud->getState() == "update"){
            $playlistId = $post["pl_ID"];
        
            $playlistResult = $this->m->getPlaylistById($playlistId);
            $playlist = $playlistResult[0];

            $this->playlistName = $playlist->pl_name;
            
        }
        
        
        $this->mediaTemp = split(",", trim($post["media_temp"], ","));
        
        $post = parent::clearBeforeInsertAndUpdate($post);
        $post = parent::setDefaultValue($post);
        
        $post["pl_lenght"] = $this->getDuration($post["pl_lenght"]);
                
        return $post;
     }
     
     function afterInsert($playlist , $playlistId){
         $this->m->insertPlaylistItem($playlistId, $this->mediaTemp);
         if($this->getMode() == "L"){
             if($this->crud->getState() == "insert"){
                 $this->autoCreateStory($playlistId, $playlist["pl_name"]);
             } else if($this->crud->getState() == "update"){
                 $this->autoUpdateStory($playlistId, $playlist["pl_name"]);
             }
             
         } 
     }
     
     private function autoCreateStory($playlistId, $playListname){
         $resolution = $this->playlist["default_layout_resolution"];
         
         $layout = $this->createLayout($playListname, $resolution);
         $layoutId = $this->m->insertLayout($layout);
         
         $display = $this->createDisplay($playListname, $resolution, $layoutId);
         $displayId = $this->m->insertDisplay($display); 
        
         $story = $this->createStory($playListname, $layoutId);
         $storyId = $this->m->insertStory($story);
         
         $this->m->insertStoryItem($storyId, $displayId, $playlistId, 100);
         
     }
     
     private function autoUpdateStory($playlistId, $playListname){
//         $resolution = $this->playlist["default_layout_resolution"];
//         
//         $layout = $this->createLayout($playListname, $resolution);
//         $layoutId = $this->m->insertLayout($layout);
//         
//         $display = $this->createDisplay($playListname, $resolution, $layoutId);
//         $displayId = $this->m->insertDisplay($display);
//         
//         $story = $this->createStory($playListname, $layoutId);
//         $storyId = $this->m->insertStory($story);
//         
//         $this->m->insertStoryItem($storyId, $displayId, $playlistId);
         
         
         
//        $playlistResult = $this->m->getPlaylistById($playlistId);
//        $playlist = $playlistResult[0];
//        $playListname = $playlist->pl_name;
        $storyResult = $this->m->getStoryByName($this->playlistName);

        $count = count($storyResult);
        if($count == 1){
            $story = $storyResult[0];
            $story->story_name = $playListname;
            
            $this->m->updateStory($story);

            $layoutId = $story->lyt_ID;

            $displayResult = $this->m->getDisplayByLayoutId($layoutId);

            foreach ($displayResult as $display) {
                $display->dsp_name = $playListname;
                $this->m->updateDisplay($display);
            }

            $layoutResult = $this->m->getLayoutById($layoutId);
            $layout = $layoutResult[0];
            $layout->lyt_name = $playListname;
            $this->m->updateLayout($layout);
            
        }

         
     }
     
     private function createLayout($name, $resolution){
        $width = $resolution["width"];
        $height = $resolution["height"];
        $layout = array("lyt_name" => $name,
                        "lyt_width" => $width,
                        "lyt_height" => $height,
                        "cpn_ID" => $this->cpnId
                        );
        return $this->setDefaultValue($layout, "insert");
     }
     
     private function createStory($name, $layoutId){
        $story = array("story_name" => $name,
                        "lyt_ID" => $layoutId,
                        "cpn_ID" => $this->cpnId
                        );
        return $this->setDefaultValue($story, "insert");
     }
     
     private function createDisplay($name, $resolution, $layoutId){
        $width = $resolution["width"];
        $height = $resolution["height"];
        return array("dsp_name" => $name,
                        "dsp_left" => "0",
                        "dsp_top" => "0",
                        "dsp_width" => $width,
                        "dsp_height" => $height,
                        "dsp_zindex" => '1',
                        "lyt_ID" => $layoutId
                        );
     }
     
     private function getDuration($string){
        $arr = explode(":", $string);
        return $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
     } 
    public function _beforeDelete($primary_key) {
        
        if($this->getMode() == "L"){
            $ret = false;
            $playlistResult = $this->m->getPlaylistById($primary_key);
            $playlist = $playlistResult[0];
            $playlistName = $playlist->pl_name;
            $storyResult = $this->m->getStoryByName($playlistName);

            $count = count($storyResult);
            if($count == 1){
                $story = $storyResult[0];
                $storyId = $story->story_ID;
                $checkStory = $this->m->checkDeleteStory($storyId);
                if($checkStory){
                    
                    $this->m->deleteStoryItem($storyId);
                    
                    $this->m->deleteStory($storyId);
                    
                    $layoutId = $story->lyt_ID;
                    
                    $displayResult = $this->m->getDisplayByLayoutId($layoutId);
                    
                    foreach ($displayResult as $display) {
                        $displayId = $display->dsp_ID;
                        $this->m->deleteDisplay($displayId);
                    }
                    
                    $this->m->deleteLayout($layoutId);
                    $ret = true;
                }
            }
            return $ret;
        } else {
            return $this->m->checkDeletePlaylist($primary_key);
        }
    }
    
    
    private function detail($detail = ""){
        $playlist_type = $this->getPlaylistType();
        $this->crud->set_table('mst_pl')
        ->set_relation_n_n('Media', 'trn_pl_has_media', 'mst_media', 'pl_ID', 'media_ID', 'media_name', 'seq_no', null, array("`mst_media`.`create_date` DESC"))
        ->set_subject($detail . ' PlayList')
        ->where("mst_pl.cpn_ID" , $this->cpnId)
        
        ->columns('pl_name','pl_desc', 'pl_lenght', 'pl_usage')
        ->display_as('pl_name', 'Name')
        ->display_as('pl_desc', 'Description')
        ->display_as('pl_lenght', 'Duration')
        ->display_as('media_name', 'Media Name')       
        ->display_as('pl_usage', 'Usage')
        ->display_as('pl_playmode', 'Play Mode')
        ->display_as('create_date', 'Create Date')
        ->display_as('update_date', 'Update Date')
        ->display_as('Media', 'Media') 
        ->display_as('pl_type', 'Playlist Type') 
        ->display_as('pl_expired', 'Expired Date')                 
        ->display_as('autoCreateStory', 'Auto Create Story')                 
                
        ->required_fields("pl_name", 'pl_lenght', "pl_type", "pl_expired")
                    

        ->fields('pl_ID', 'pl_name', 'pl_desc', 'pl_lenght', 'pl_usage', 'pl_type', 'pl_expired', 
                'Media', "media_temp", "page", "cpn_ID", "event",
                "create_date", "create_by", "update_date", "update_by")
                
        ->field_type("pl_ID", "hidden") 
        ->field_type("media_temp", "hidden")    
        ->field_type("event", "hidden") 
        ->callback_column("pl_lenght", array($this, "_length"))  
       ->callback_column("pl_usage", array($this, "_usage")) 
        ->callback_field("pl_lenght", array($this, "_pl_lenght"))
        ->callback_field("pl_usage", array($this, "_pl_usage")) 
//        ->callback_field("autoCreateStory", array($this, "_autoCreateStory")) 
        ->callback_after_insert(array($this, "afterInsert"))
        ->callback_after_update(array($this, "afterInsert"))
//        ->add_action('Clone', '', 'demo/action_more','ui-icon-document', array($this,'_clone'))
        ;
        
        
        $state = $this->crud->getState();
//        if($state !== "add"){
//            $this->crud->field_type("autoCreateStory", "hidden") ;
//        }
        
        if($state === "add" || $state === "edit"){
            
//            $where = array("media_type" => $playlist_type);
            $mediaDaoList = $this->m->selectMedia();
            $groupDaoList = $this->m->selectCat();
            $obj = new stdClass();
            $obj->cat_ID = 0;
            $obj->cat_name = "all";
            array_unshift($groupDaoList, $obj);
            $groupDaoList = json_encode($groupDaoList);
            
            
            $mediaXmlList = array();
            foreach ($mediaDaoList as $value) {
                $mediaXmlList[$value->media_ID] = array("cat_ID" => $value->cat_ID, 
                                                        "lenght" => $value->media_lenght / 1000,
                                                        "type" => $value->media_type);
            }
            
            $mediaXmlList = json_encode($mediaXmlList);
            $this->crud->setCustomScript("var mediaList = $mediaXmlList;\n var groupList = $groupDaoList; ");
            if($state === "add"){
                $this->crud->field_type("pl_type", "enum", array("video", "image", "scrolling text", "Web page", "RSS feed", "Streaming"), $playlist_type);
            }
        }
    }
}