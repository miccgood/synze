<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class GroupPlayer extends SpotOn {

    function __construct() {
        parent::__construct();
        $this->config->load('groupplayer', true);
        $this->groupplayer = $this->config->item('groupplayer');
        $this->autoSetDefaultValue = true;
        $this->indexArray = array("Resolution", "next");
    }
    
    public function index($state = null, $mstTmnGrpId = -1) {
        
        
       $state = $this->crud->getState();
       
       $selected_value = ($state == "add" ? $this->m->getSelectUserReceiveAlert() : array());
       
        $this->crud->set_table('mst_tmn_grp')
//        ->set_relation_n_n('user_manager_ID', 'trn_permission', 'mst_permission', 'user_ID', 'permission_ID', 'page_name','seq')
        ->set_relation_n_n('user_manager_ID', 'mst_user_manager', 'mst_user', 'tmn_grp_ID', 'user_ID', 'user_username', 'user_manager_seq', 
                array('mst_user.cpn_ID' => $this->cpnId), 
                array('user_username'), $selected_value)
        ->set_relation('tmn_grp_master_ID', 'mst_tmn', 'tmn_name', array('mst_tmn.cpn_ID' => $this->cpnId, "mst_tmn.tmn_grp_ID" => $mstTmnGrpId))
        ->where("mst_tmn_grp.cpn_ID" , $this->cpnId)
        ->columns('tmn_grp_name','tmn_grp_desc', 'tmn_grp_width', 'tmn_grp_height')
        ->set_subject('Group Player')
        
        ->display_as('tmn_grp_name', 'Name')
        ->display_as('tmn_grp_desc', 'Description')
        ->display_as('tmn_grp_master_ID', 'Master of Group')
        ->display_as('user_manager_ID', 'Receive Notification')
        ->display_as('tmn_grp_width', 'Width')
        ->display_as('tmn_grp_height', 'Height')
        ->display_as('Resolution', 'Virtual Screen Size')
         ->field_type('tmn_grp_ID','hidden')       
//        ->field_type('next','invisible')
        
        ->callback_column('tmn_grp_name',array($this,'callbackLytName'))
        ->columns_align(array('tmn_grp_name' => 'right',
            'tmn_grp_desc' => 'right',
            'tmn_grp_width' => 'right',
            'tmn_grp_height' => 'right',
            'Resolution' => 'right',
            'create_date' => 'right',
            'update_date' => 'right'))
                
        ->fields("tmn_grp_ID", "tmn_grp_name","tmn_grp_desc", "Resolution", "tmn_grp_width", "tmn_grp_height", 'tmn_grp_master_ID', 'user_manager_ID', 'cpn_ID',
                "create_date", "create_by", "update_date", "update_by")
        ->callback_field("Resolution", array($this,'callback_resolution'))
                
        ->callback_after_insert(array($this, "afterInsert"))
        ->callback_after_update(array($this, "afterInsert"))
                
        ->required_fields('tmn_grp_name', 'tmn_grp_width', 'tmn_grp_height')
                
        ;
        $this->output();
        
    }
    
    function clearBeforeInsertAndUpdate($files_to_insert = "", $field_info = "" , $file = null, $row = null) {
        
        $this->next = $files_to_insert["next"];
        $files_to_insert = parent::clearBeforeInsertAndUpdate($files_to_insert);
        $files_to_insert = parent::setDefaultValue($files_to_insert);
        return $files_to_insert;
    }
    
    function afterInsert($groupplayer , $groupplayerId){
         if($this->next == "Y"){
//             redirect("display?tmn_grp_id=". $groupplayerId);
         }
     }
     
    function callbackLytName($value = '', $primary_key = null)
    {
        return '<a href="'.site_url('groupplayerscreen?tmn_grp_id='.$primary_key->tmn_grp_ID).'">'.$value.'</a>';
    }


    public function callGroupDetail($primary_key = '', $row)
    {
        return site_url('group').'/'.$primary_key;
    }
    
        
    function callback_resolution($data = "", $primary_key = null, $row = null, $rows = null, $tag = null) {
        $ret = "<select id='select_resolution'>";
        foreach ($this->groupplayer['resolution'] as $keys => $values) {
             foreach ($values as $key => $value) {
                 
                $res2 = array_pop($value);
                $res1 = array_pop($value);

                $ret .= "<option value='".$res1.','.$res2."'>" . $key ;

                $option = "";
                if($res1 != "" || $res2 != ""){
                    $option .= ' ( '.$res1.' x ' . $res2. ' )';
                }
                
                $ret .= $option . "</option>";
             }
        }             
         $ret .= "</select>";
         
         
         $ret .= "<script>  "
                 . "$(function(){ "
                 . "    $('#select_resolution').change(function(){"
                 . "        var value = $(this).val().split(',');"
                 . "        var width = value[0];"
                 . "        var height = value[1];"
                 . "        $('#field-tmn_grp_width').val(width);"
                 . "        $('#field-tmn_grp_height').val(height);"
                 . "    }) "
                 . "});</script>";
        
        return $ret;
    }
    
    public function _beforeDelete($primary_key) {
//        if($this->m->checkDeleteLayout($primary_key)){
//            $this->m->deleteDisplayByLayoutId($primary_key);
            return true;
//        } else {
//            return false;
//        }
    }
    
}