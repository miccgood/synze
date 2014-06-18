<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class StoryItem extends SpotOn {

    private $storyId = 0;
    
    function __construct() {
        $this->layoutId = null;
        $this->autoSetDefaultValue = TRUE;
        parent::__construct();
        $this->storyId = 0;
        
//        $this->config->load('story', true);
//        $this->layout = $this->config->item('story');
    }
    
    function getStoryId(){
        return $this->storyId;
    }
    
    function ajax(){
        //array
        $post = $this->input->post();
        
        $story = $post["story"];
        $storyItem = $post["storyItem"];
        $storyId = $story["story_id"] ;
//        $layoutId = $this->getPkFormReq("lyt_id");
        $story = $this->setDefaultValue($story);
        
        $story["update_date"] = date("YmdHis", time());
        $story["update_by"] = $this->userId;
        $story["cpn_ID"] = $this->cpnId;
        if($this->nullToZero($storyId, 0) === 0){
            $story["create_date"] = date("YmdHis", time());
            $story["create_by"] = $this->userId;
            $storyId = $this->m->insertStory($story);
        }else{
            $this->m->updateStoryById($story, $storyId);
        }
        if($this->nullToZero($storyId, 0) !== 0){
            $this->m->deleteStoryItem($storyId);
            foreach ($storyItem as $value) {
                $dspId = $value["dsp_ID"];
                $plId = $value["pl_ID"];
                $volumn = $value["volumn"];
                if( $dspId !== FALSE && $plId !== FALSE && $dspId !== null && $plId !==  null){
                    $this->m->insertStoryItem($storyId, $dspId, $plId, $volumn);
                }
            }
            $ret = array("success" => true, "story_id" => $storyId);
        }else{
            $ret = array("success" => false);
        }
        
        echo json_encode($ret);
    }
    
    
    public function index() {
        
        $storyId = $this->nullToZero($this->input->get("story_id"), 0);
        $layoutId = $this->nullToZero($this->input->get("lyt_id"), 0);
        
        
        $story = $this->m->getStoryById($storyId);
        if($story){
            $this->storyId = $story[0]->story_ID;
            $this->layoutId = $layoutId = $story[0]->lyt_ID;
        }
        
        
        $this->session->set_userdata("story_id", $storyId);
        $this->session->set_userdata("lyt_id", $layoutId);
        
        $playlistDao = $this->m->getPlaylistDropDownForStoryItemByCpnId($this->cpnId);
        $playlistXml = json_encode($playlistDao);
        
        $data = $this->getDefaultValue($storyId, $layoutId);
        
        $this->crud->set_table('mst_dsp')
//        ->columns("dsp_name", "pl_desc", "playlist", "duration", "volumn")
        ->columns("dsp_name", "playlist", "duration", "volumn")
        ->where("lyt_ID", $layoutId)
        ->where("lyt_ID != ", 0)
        ->set_subject('Story')
        
        ->display_as('dsp_name', 'Zone')
        ->display_as('pl_desc', 'Description')       
        ->display_as('PlayList', 'PlayList')        
        ->display_as('duration', 'Duration')          
        ->display_as('volumn', 'Volumn')  
                
        ->fields("story_name","story_desc", "lyt_ID", "page")
        
        ->field_type('page', 'hidden', 'storyItem')
                
        ->setCustomScript("var playlist = $playlistXml;")
        ->set_default_value($data)
        ->setBackUrl(site_url('story'))
        ;
        
        
        
        
        $this->output();
    }
    
    
    private function getDefaultValue($storyId, $layoutId){
        $data = array();
        $data["story"] = $this->m->getStoryById($storyId);
        $data["storyItem"] = $this->m->getStoryItemByStoryId($storyId);
        $data["layoutAll"] = $this->m->getLayout();
        $data["displayAll"] = $this->m->getDisPlay();
        
        $layoutId = (count($data["story"]) == 0 ? 0 : $this->nullToZero($this->getValueFromObj($data["story"][0], "lyt_ID"), 0));
        $data["layout"] = $this->m->getLayoutById($layoutId);
        $data["display"] = $this->m->getDisplayByLayoutId($layoutId);
        $data["permissionEdit"] = $this->permissionEdit;
        return $data;
    }
    protected function setDefaultAction() {
        parent::setDefaultAction();
        $this->crud->unset_edit()->unset_add()->unset_delete();
    }
    
    public function _beforeDelete($primary_key) {
        //ที่หน้านี้ไม่มีการลบ จะไปลบที่ story
        return FALSE;
    }
}