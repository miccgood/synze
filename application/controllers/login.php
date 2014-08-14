<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('spot_on_model', "m");
        
        $this->config->load('permission', true);
        $this->configPermission = $this->config->item('permission');
        
    }
    
    public function check() {
        $username = $this->input->post("user");
        $password = $this->input->post("pass");
        if($username !== FALSE && $password !== FALSE){
            $user = $this->m->checkLogin($username, $password);
            if($user !== NULL){
                $permission = $this->m->getPermission($user->user_ID);
                
                $this->session->set_userdata("cpnID", $user->cpn_ID);
                $this->session->set_userdata("userID", $user->user_ID);
                $this->session->set_userdata("userTypeCode", $user->user_type_code);
                $this->session->set_userdata("permission", $permission);
                
                $this->session->set_userdata("displayName",  $user->user_displayname);
                $this->session->set_userdata("cpnName", $user->cpn_name);

                $adminCodeList = $this->configPermission["adminCodeList"];
                $superAdminCodeList = $this->configPermission["superAdminCodeList"];
                if(in_array($user->user_type_code, $superAdminCodeList)){
                    redirect("super");
                }else if(in_array($user->user_type_code, $adminCodeList)){
                    redirect("admin");
                } else {
                    redirect("media");
                }
            }else{
                $data["error"] = "Username or Password Wrong";
                $this->load->view('login', $data);
            }
        }
    }
    
    public function logout() {
        $message_login = $this->session->userdata("message_login");
        $this->session->set_userdata("message_login", "");
        $this->session->sess_destroy();
//        redirect("login");
        $data["error"] = $message_login;
        $this->load->view('login', $data);
    }
    
    public function index()
    {
        $message_login = $this->session->userdata("message_login");
        $data["error"] = $message_login;
        $this->load->view('login', $data);
    }
        
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */