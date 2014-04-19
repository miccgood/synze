<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout extends SpotOn {

    function __construct() {
        parent::__construct();
        $this->config->load('layout', true);
        $this->layout = $this->config->item('layout');
        $this->autoSetDefaultValue = true;
    }
    
    public function index() {
        
//        $cat_id = $this->uri->segment(4);
        
        
//        $crud = new grocery_CRUD();
//        $crud->set_relation($field_name, $related_table, $related_title_field, $crud, $order_by)
//$crud->set_relation($field_name, $related_table, $related_title_field)
        $this->crud->set_table('mst_lyt')
        ->set_relation('lyt_ID', 'mst_dsp', 'lyt_ID')
        ->where("mst_lyt.cpn_ID" , $this->cpnId)
        ->columns('lyt_name','lyt_desc', 'lyt_width', 'lyt_height')
//        ->set_relation('cat_ID', 'mst_media', 'media_name')
        ->set_subject('Layout')
        
        ->display_as('lyt_name', 'Name')
        ->display_as('lyt_desc', 'Desc')
                
        ->display_as('lyt_width', 'Width')
        ->display_as('lyt_height', 'Height')
        ->display_as('Resolution', 'Screen Size')
                
        ->callback_column('lyt_name',array($this,'callbackLytName'))
        ->columns_align(array('lyt_name' => 'right',
            'lyt_desc' => 'right',
            'lyt_width' => 'right',
            'lyt_height' => 'right',
            'Resolution' => 'right',
            'create_date' => 'right',
            'update_date' => 'right'))
                
        ->fields("lyt_name","lyt_desc", "Resolution", "lyt_width", "lyt_height", 'cpn_ID',
                "create_date", "create_by", "update_date", "update_by")
        ->callback_field("Resolution", array($this,'callback_resolution'))
                
        ->callback_before_insert(array($this,'clearResolution'))
        ->callback_before_update(array($this,'clearResolution'))
        ;
        $this->output();
    }
    
    function callbackLytName($value = '', $primary_key = null)
    {
        return '<a href="'.site_url('display?lyt_id='.$primary_key->lyt_ID).'">'.$value.'</a>';
    }


    public function callGroupDetail($primary_key = '', $row)
    {
        return site_url('group').'/'.$primary_key;
    }
    
//    public function indexs() {
//        
////        $cat_id = $this->uri->segment(4);
//        
//        
////        $crud = new grocery_CRUD();
////        $crud->set_relation($field_name, $related_table, $related_title_field, $crud, $order_by)
////$crud->set_relation($field_name, $related_table, $related_title_field)
//        $this->crud->set_table('mst_lyt')
//        ->set_relation('lyt_ID', 'mst_dsp', 'lyt_ID')
////        ->set_relation('cat_ID', 'mst_media', 'media_name')
//        ->set_subject('Layout')
//        
//        ->display_as('lyt_width', 'Width')
//        ->display_as('lyt_height', 'Height')
//                
//        ->columns("lyt_name","lyt_desc", "lyt_width", "lyt_height", "create_date", "update_date")
//        ->fields("lyt_name","lyt_desc", "lyt_width", "lyt_height", "Resolution", "create_date", "update_date")
//        ->callback_field("Resolution", array($this,'callback_resolution'))
//                
//        ->callback_before_insert(array($this,'clearResolution'))
//        ->callback_before_update(array($this,'clearResolution'))
//        ;
//        $this->output();
//    }
    
    
    
    
    
    
//    function clearResolution($data = "", $primary_key = null, $row = null, $rows = null, $tag = null) {
//        return $data;
//    }
        
    function callback_resolution($data = "", $primary_key = null, $row = null, $rows = null, $tag = null) {
//        $ret = "<div class='".$row->crud_type."_label' id='field-".$row->name."'></div>";
//        $ret =  "<input id='field-".$row->name."' name='".$row->name."' type='text' value='".$data."' class='numeric' maxlength='".$row->db_max_length."' />";
        $ret = "<select id='select_resolution'>";
        foreach ($this->layout['resolution'] as $keys => $values) {
             foreach ($values as $key => $value) {
                  $ret .= "<option value='".$value["width"].','.$value["height"]."'>" . $key ;
                  
                  if($value["width"] != "" || $value["height"] != ""){
                      $ret .= ' (' . $value["width"].'x'.$value["height"].')';
                  }
                  $ret .= "</option>";
             }
        }             
         $ret .= "</select>";
         
         
         $ret .= "<script>  "
                 . "$(function(){ "
                 . "    $('#select_resolution').change(function(){"
                 . "        var value = $(this).val().split(',');"
                 . "        var width = value[0];"
                 . "        var height = value[1];"
                 . "        $('#field-lyt_width').val(width);"
                 . "        $('#field-lyt_height').val(height);"
                 . "    }) "
                 . "});</script>";
        
        return $ret;
    }
    
    public function _beforeDelete($primary_key) {
        return $this->m->checkDeleteLayout($primary_key);
    }
    
}