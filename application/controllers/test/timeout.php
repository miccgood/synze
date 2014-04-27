<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Timeout extends Thread {

    function __construct() {
        parent::__construct();
    }
    
    function sleep() {
        
    }
    
    function ajax() {
        set_time_limit (2);
        $seconds = $this->uri->segment(3);
        sleep( ( $seconds ? $seconds : 1));
        echo json_encode(array("result" => "true"));
    }
    
    public function index() {
        $this->load->view("programmer", array("result" => "Success"));
    }
    
    public function run() {
        
    }
}
