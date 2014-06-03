<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Authentication {

    private $CI;
    function __construct() {
         $this->CI = &get_instance();
    }
    
    private function loadSession(){
        if(!isset($this->CI)){
            $this->CI = &get_instance();
        }
        
        if (!isset($this->CI->session))
        {
            $this->CI->load->library('session');
        }
        return TRUE;
        
    }
    
    public function checkLogin() {
        if($this->loadSession()){
           $cpnId = $this->CI->session->userdata('cpnID');
           if(is_null($cpnId) || $cpnId === FALSE){
                if($this->CI->router->class !== "login"){
                    redirect('login');
                }
           }else{
               if($this->CI->router->class === "login" && $this->CI->router->method === "index"){
                   redirect('media');
               }
           }
        }
        return ;
    }
    
}