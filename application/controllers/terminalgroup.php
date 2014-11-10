<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TerminalGroup extends SpotOnLov {
    function __construct() {
        parent::__construct();
        $this->autoSetDefaultValue = true;
    }
    
    function countTerminal($value, $row) {
        
        
        $E = @$this->db->query('select count(tmn_id) as result from mst_tmn where tmn_grp_ID = ?', array($row->tmn_grp_ID))->row()->result;
        return ($E) ? (string) $E : '0'; // or: return ($E) ? (string) $E : '~empty~';
    }

    function index() {

        $this->crud->set_table('mst_tmn_grp')
        ->set_subject('Terminal Group')
        ->where("cpn_ID" , $this->cpnId)
        ->fields('tmn_grp_name', 'cpn_ID')
        ->columns('tmn_grp_name','count_terminal')
//            ->order_by("cat_name", "DESC")
            ->callback_column('tmn_grp_name',array($this,'callbackTmnGrpName'))
            ->callback_column('count_terminal', array($this, 'countTerminal'))

            ->display_as('tmn_grp_name', 'GroupName')
            ->display_as('count_terminal', 'Total')
        ;

        $this->output();
        
    }

    function callbackTmnGrpName($value = '', $primary_key = null)
    {
        return '<a href="'.site_url('terminal?tmn_grp_id='.$primary_key->tmn_grp_ID).'">'.$value.'</a>';
    }
    
    public function _beforeDelete($primary_key) {
        return $this->m->checkDeleteTerminalGroup($primary_key);
    }
}