<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Story extends SpotOn {

    
    function __construct() {
        $this->layoutId = null;
        $this->indexArray = array("page");
        $this->autoSetDefaultValue = TRUE;
        parent::__construct();
        
    }
    
    public function index() {
        
        
        $this->crud->set_table('mst_story')
                
        ->where("mst_story.cpn_ID" , $this->cpnId)
        ->set_relation('lyt_ID', 'mst_lyt', 'lyt_name')
        ->columns("story_name", "story_desc", "lyt_ID")
        ->callback_column('story_name',array($this,'callbackStoryName'))
        ->set_subject('Story')
        
        ->display_as('story_name', 'Name')
        ->display_as('story_desc', 'Description')
        ->display_as('lyt_ID', 'Layout')
        ->display_as('Zone', 'Zone')
        ->display_as('PlayList', 'PlayList')       
                
//                ->display_as('dsp_ID', 'dsp_ID')
        ->fields("story_name","story_desc", "lyt_ID", "page")
        ->field_type('page', 'hidden', 'story')
                
        ->add_action('Edit', '', 'storyitem/index','ui-icon-pencil', array($this,'edit_participant'))
        ->unset_edit();
        ;
        $this->output();
    }
    
    public function edit_participant($primary_key , $row)
    {
       return 'storyitem?story_id=' . $primary_key . '&lyt_id=' . $row->lyt_ID;
    }
     
    function callbackStoryName($value = '', $primary_key = null, $row = "")
    {
        return '<a href="'.site_url('storyitem?story_id='.$primary_key->story_ID).'&lyt_id='.$primary_key->lyt_ID.'">'.$value.'</a>';
    }


    public function callGroupDetail($primary_key = '', $row)
    {
        return site_url('group').'/'.$primary_key;
    }
    
    public function _beforeDelete($primary_key) {
        $ret = $this->m->checkDeleteStory($primary_key);
        if($ret){
            $this->m->deleteTrnDspHasPlByStoryId($primary_key);
            return true;
        } else {
            return $ret;
        }
    }
    
}