<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PlayList extends SpotOn {

    
    function __construct() {
        parent::__construct();
        $this->indexArray = array("Media", "media_temp", "mediaTemp", "event");
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
    
    
    public function fclone() {
        
        $this->detail("Clone");
        
        $plId = $this->url->segment(4);
        $playlistDaoList = $this->m->getPlaylistById($plId);
//        $playlistDao = $playlistDaoList[0];
        //
        $playlistXmlList = array();
        foreach ($playlistDaoList as $value) {
            $playlistXmlList[$value->pl_ID] = $value;
        }
        
        $this->crud->appendCustomScript("var = " . json_encode($playlistXmlList) . "; ");
//       
        $this->output();
    }
    
    public function index() {
        $this->detail();
       
        $this->output();
    }
    
    function _pl_usage($value = "" , $pk = "", $row = "" , $rows = "") {
        return "<div id='progressbar' style='display:none;'><div class='progress-label'>$value</div></div> <input type='hidden' name='pl_usage' id='field-pl_usage' value='$value'/>";
    }
     
    function clearBeforeInsertAndUpdate($post) {
        $this->mediaTemp = split(",", trim($post["media_temp"], ","));
        
        $post = parent::clearBeforeInsertAndUpdate($post);
        $post = parent::setDefaultValue($post);
        
        $string = $post["pl_lenght"];
        $arr = explode(":", $string);
        $count = $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
        
        $post["pl_lenght"] = $count;
                
        
         return $post;
     }
     
     function afterInsert($playlist , $playlistId){
         $this->m->insertPlaylistItem($playlistId, $this->mediaTemp);
     }
     
    public function _beforeDelete($primary_key) {
        return $this->m->checkDeletePlaylist($primary_key);
    }
    
    
    private function detail($detail = ""){
        $playlist_type = $this->getPlaylistType();
        $this->crud->set_table('mst_pl')
        ->set_relation_n_n('Media', 'trn_pl_has_media', 'mst_media', 'pl_ID', 'media_ID', 'media_name', 'seq_no', null, array("`mst_media`.`create_date` DESC"))
        ->set_subject($detail . ' PlayList')
        ->where("mst_pl.cpn_ID" , $this->cpnId)
        
        ->columns('pl_name','pl_desc', 'pl_lenght', 'pl_usage')
        ->display_as('pl_name', 'Name')
        ->display_as('pl_desc', 'Desc')
        ->display_as('pl_lenght', 'Duration')
        ->display_as('media_name', 'Media Name')       
        ->display_as('pl_usage', 'Usage')
        ->display_as('pl_playmode', 'Play Mode')
        ->display_as('create_date', 'Create Date')
        ->display_as('update_date', 'Update Date')
        ->display_as('Media', 'Media') 
        ->display_as('pl_type', 'Playlist Type') 
        ->display_as('pl_expired', 'Expired Date')                 
                
        ->required_fields("pl_name", 'pl_lenght', "pl_type", "pl_expired")
                    

        ->fields('pl_ID', 'pl_name', 'pl_desc', 'pl_lenght', 'pl_usage', 'pl_type', 'pl_expired', 
                'Media', "media_temp", "page", "cpn_ID", "event",
                "create_date", "create_by", "update_date", "update_by")
        ->field_type("pl_ID", "hidden") 
        ->field_type("media_temp", "hidden")    
        ->field_type("event", "hidden") 
        ->callback_field("pl_usage", array($this, "_pl_usage")) 
        ->callback_after_insert(array($this, "afterInsert"))
        ->callback_after_update(array($this, "afterInsert"))
//        ->add_action('Clone', '', 'demo/action_more','ui-icon-document', array($this,'_clone'))
        ;
        
        
        $state = $this->crud->getState();
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
                $this->crud->field_type("pl_type", "enum", array("video", "image", "scrolling test"), $playlist_type);
            }
        }
    }
}