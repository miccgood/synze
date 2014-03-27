<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('spot_on_model', "m");
    }
    
    public function check() {
        $username = $this->input->post("user");
        $password = $this->input->post("pass");
        if($username !== FALSE && $password !== FALSE){
            $user = $this->m->checkLogin($username, $password);
            if($user !== NULL){
                $this->session->set_userdata("cpnID", $user->cpn_ID);
                $this->session->set_userdata("userID", $user->user_ID);
                redirect("media");
            }else{
                $data["error"] = "Username or Password Wrong";
                $this->load->view('login', $data);
            }
        }
    }
    
    public function logout() {
        $this->session->sess_destroy();
        redirect("login");
    }
    
    public function index()
    {
        $data["error"] = "";
        $this->load->view('login', $data);
    }
        
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */