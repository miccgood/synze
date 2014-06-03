<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PlayList extends SpotOn {

    
    function __construct() {
        parent::__construct();
        $this->indexArray = array("Media", "mediaTemp");
    }
    
    public function index() {
        
        $this->crud->set_table('mst_pl')
        ->set_relation_n_n('Media', 'trn_pl_has_media', 'mst_media', 'pl_ID', 'media_ID', 'media_name', 'seq_no')
        ->set_subject('PlayList')
        
        
        ->columns('pl_name','pl_desc', 'pl_lenght', 'pl_usage')
        ->display_as('pl_name', 'Name')
        ->display_as('pl_desc', 'Desc')
        ->display_as('pl_lenght', 'Lenght')
        ->display_as('media_name', 'Media Name')       
        ->display_as('pl_usage', 'Usage')
        ->display_as('pl_playmode', 'Play Mode')
        ->display_as('create_date', 'Create Date')
        ->display_as('update_date', 'Update Date')
        ->display_as('Media', 'Media') 
        
        ->fields('pl_name', 'pl_desc', 'pl_lenght', 'pl_usage', 'Media', "page")
        ->callback_after_insert(array($this, "afterInsert"))
        ->callback_after_update(array($this, "afterInsert"))
        ;
        
        
        $crud = new Grocery_CRUD;
        $state = $crud->getState();
        if($state === "add" || $state === "edit"){
            $mediaDaoList = $this->m->selectMedia();
            $groupDaoList = json_encode($this->m->selectCat());
            $mediaXmlList = array();
            foreach ($mediaDaoList as $value) {
                $mediaXmlList[$value->media_ID] = array("cat_ID" => $value->cat_ID, 
                                                        "lenght" => $value->media_lenght,
                                                        "type" => $value->media_type);
            }
            
            $mediaXmlList = json_encode($mediaXmlList);
            $this->crud->setCustomScript("var mediaList = $mediaXmlList;\n var groupList = $groupDaoList; ");
        }
       
        $this->output();
    }
    
    function clearBeforeInsertAndUpdate($files_to_upload) {
        $this->media = $files_to_upload["Media"];
        $files_to_upload = parent::clearBeforeInsertAndUpdate($files_to_upload);
//        $this->media = $files_to_upload["Media"];
//        unset($files_to_upload["Media"]);
         return $files_to_upload;
     }
     
     function afterInsert($playlist , $playlistId){
         $this->m->insertPlaylistItem($playlistId, $this->media);
     }
}