<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Group extends SpotOnLov {
    function __construct() {
        parent::__construct();
        $this->autoSetDefaultValue = TRUE;
    }
    
//    function countMedia($value, $row) {
//        $E = @$this->db->query('select count(media_id) as result from mst_media where cat_id = ?', array($row->cat_ID))->row()->result;
//        return ($E) ? (string) $E : '0'; // or: return ($E) ? (string) $E : '~empty~';
//    }

    function index() {

        $this->crud->set_table('mst_media_cat')
        ->set_subject('Group')
        ->where("cpn_ID" , $this->cpnId)
        ->columns('cat_name','count_media')
            ->order_by("cat_name", "DESC")
            ->callback_column('cat_name',array($this,'callbackCatName'))
            ->callback_column('count_media', array($this, 'countMedia'))
            ->display_as('cat_id', 'ID')
            ->display_as('cat_name', 'GroupName')
            ->display_as('sum_media', 'Total')
                
            ->fields('cat_ID', 'cat_name', 'cpn_ID')
            ->field_type('cat_ID', 'hidden')
            ->field_type('cpn_ID', 'hidden', $this->cpnId)
                
            ->required_fields("cat_name")

        ;

        $this->output();
        
    }
    
    public function _beforeDelete($primary_key) {
        return $this->m->checkDeleteGroupMedia($primary_key);
    }
    

//    function callbackCatName($value = '', $primary_key = null)
//    {
//        return '<a href="'.site_url('media?cat_id='.$primary_key->cat_ID).'">'.$value.'</a>';
//    }
//
//
//    public function callGroupDetail($primary_key = '', $row)
//    {
//        return site_url('group').'/'.$primary_key;
//    }
}