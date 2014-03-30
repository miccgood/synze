<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Monitor extends SpotOn {
    public $setInterval = 0;
    function __construct() {
        parent::__construct();
        $this->config->load('monitor', true);
        $this->monitor = $this->config->item('monitor');
        $this->setInterval = $this->monitor["setInterval"];
    }
    
    function countTerminal($value, $row) {
        
        
        $E = @$this->db->query('select count(tmn_id) as result from mst_tmn where tmn_grp_ID = ?', array($row->tmn_grp_ID))->row()->result;
        return ($E) ? (string) $E : '0'; // or: return ($E) ? (string) $E : '~empty~';
    }

    function ajaxRefresh(){
        $post = $this->input->post();
        
        $ids = $post["monitorIds"];
        
        require_once 'test.php';
//        
        $test = new Test();
        $test->index();
        
        
        $result = $this->m->getTerminalStatusInIds($ids);
        $ret = array();
        foreach ($result as $key => $value) {
            $status = array(
                "tmn_monitor" => $value->tmn_monitor,
                "tmn_status_id" => $value->tmn_status_id,
                "tmn_status_message" => $value->tmn_status_message,
                "tmn_status_update" => $value->tmn_status_update,
                "tmn_status_upload_id" => $value->tmn_status_upload_id,
                "tmn_status_upload_message" => $value->tmn_status_upload_message,
                "tmn_status_upload_update" => $value->tmn_status_upload_update,
                "incedent_per_day" => $value->incedent_per_day,
                "incedent_per_month" => $value->incedent_per_month
                
            );
            $ret[$value->tmn_ID] = $status;
        }
        echo json_encode($ret);
    } 
    
    function index() {
        
        $this->crud->set_table('mst_tmn')
        ->set_subject('Monitor')
        ->where("mst_tmn.cpn_ID" , $this->cpnId)
        ->set_relation("tmn_grp_ID", "mst_tmn_grp", "tmn_grp_name")
        ->columns('tmn_grp_ID', 'tmn_name', 'tmn_status_id', 'upload_status_id', 'incedent_per_day', 'incedent_per_month')
            ->order_by("tmn_name", "DESC")
            ->display_as('tmn_grp_ID', 'Player Group')
            ->display_as('tmn_name', 'Player')
            ->display_as('tmn_desc', 'Desc')
            ->display_as('tmn_os', 'OS')
              
            ->display_as('tmn_uuid', 'UUID')
            ->display_as('tmn_regis_date', 'Register Date')
                
            ->display_as('tmn_status_id', 'Player Status')
            ->display_as('tmn_status_message', 'Message')
            ->display_as('tmn_status_update', 'Status Update')
            ->display_as('upload_status_id', 'Upload Status')
            ->display_as('incedent_per_day', 'Incedent Day')
            ->display_as('incedent_per_month', 'Incedent Month')    
                
        
        ->callback_column("tmn_status_id", array($this, "_tmn_status_id"))
        ->callback_column("upload_status_id", array($this, "_upload_status_id"))
//        ->callback_column("Story", array($this, "_story"))
        ->set_default_value($this->monitor) 
        ;

        $this->output();
        
    }
    
    
    function _upload_status_id($value = '', $row = null, $a = "", $pk = ""){
        $message = $row->tmn_status_upload_message;
        $active = $row->tmn_monitor;

        $color = "#cccccc";
        
        if($active != null && $active == 1){
            switch ($value) {
                case 1:
                    $color = "red";
                    break;
                case 2:
                    $color = "yellow";
                    break;
                case 3:
                    $color = "green";
                    break;

                default:
                    $color = "red";
                    break;
            }
        }  
            
            
        if($message == null || $active == null || $active != 1){
            return '<div id="border-circle" >'
                    . '<div id="circle" style="background: ' . $color .';"></div>'
                . '</div> ';
        } else {
            $message = str_replace(",", "<br/>", $message);
            return "<font style='color:$color;'> $message </font>";
        }
        
        
    }
    function _tmn_status_id($value = '', $row = null, $a = "", $pk = ""){
        $message = $row->tmn_status_message;
        $active = $row->tmn_monitor;

        $color = "#cccccc";
        
        if($active != null && $active == 1){
            switch ($value) {
                case 1:
                    $color = "red";
                    break;
                case 2:
                    $color = "yellow";
                    break;
                case 3:
                    $color = "green";
                    break;

                default:
                    $color = "red";
                    break;
            }
        }  
            
            
         if($message == null || $active == null || $active != 1){
            return '<div id="border-circle" >'
                    . '<div id="circle" style="background: ' . $color .';"></div>'
                . '</div> ';
        } else {
            $message = str_replace(",", "<br/>", $message);
            return "<font style='color:$color;'> $message </font>";
        }
        
        
    }
    function callbackTmnGrpName($value = '', $primary_key = null)
    {
        return '<a href="'.site_url('terminal?tmn_grp_id='.$primary_key->tmn_grp_ID).'">'.$value.'</a>';
    }
    
    
    function setDefaultAction() {
        parent::setDefaultAction();
        $this->crud->unset_add()->unset_edit()->unset_delete();
    }
}