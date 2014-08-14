<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Company extends SpotOnLov {
    function __construct() {
        parent::__construct();
        $this->autoSetDefaultValue = TRUE;
    }
    
//    function countMedia($value, $row) {
//        $E = @$this->db->query('select count(media_id) as result from mst_media where cat_id = ?', array($row->cat_ID))->row()->result;
//        return ($E) ? (string) $E : '0'; // or: return ($E) ? (string) $E : '~empty~';
//    }

    function index() {

        $this->crud->set_table('mst_cpn')
        ->set_subject('Company')
//        ->where("cpn_ID" , $this->cpnId)
//        ->columns('cpn_name','count_media')
//            ->order_by("cpn_name", "DESC")
//            ->callback_column('cat_name',array($this,'callbackCatName'))
//            ->callback_column('count_media', array($this, 'countMedia'))
            ->display_as('cpn_ID', 'ID')
            ->display_as('cpn_name', 'Company Name')
//            ->display_as('sum_media', 'Total')
                
            ->fields('cpn_ID', 'cpn_name')
            ->field_type('cpn_ID', 'hidden')
//            ->field_type('cpn_ID', 'hidden', $this->cpnId)
                
            ->required_fields("cpn_name")

        ;

        $this->output();
        
    }
    
    public function _beforeDelete($primary_key) {
        return $this->m->checkDeleteCompany($primary_key);
    }
}