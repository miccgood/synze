<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test extends SpotOn {

    function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $terminalList = $this->m->getTerminal();
//        for($i = 0 ; $i < 10 ; $i++){
            
            foreach ($terminalList as $terminal) {
                $terminal->tmn_monitor = rand(0,1);
                $this->randomStatus($terminal);
                $this->randomUploadStatus($terminal);
                $this->randomIncedent($terminal);
                $this->m->updateTerminal($terminal);
            }
//        }
        
    }
    
    private function randomStatus($terminal){
        $ran = rand(1,3);
        $terminal->tmn_status_id = $ran;
        $terminal->tmn_status_message = "status : " . $this->randomString() . ", test : test";        
    }
    
    private function randomUploadStatus($terminal){
        $ran = rand(1,3);
        $terminal->tmn_status_upload_id = $ran;
        $terminal->tmn_status_upload_message = "upload : " .  $this->randomString() . ", test : test";
    }
    
    private function randomIncedent($terminal){
        $ranPerDay = rand(0, 100);
        $ranPerMonth = rand(0, 100);
        $terminal->incedent_per_day = $ranPerDay;
        $terminal->incedent_per_month = $ranPerMonth;
    }
    
    private function randomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randstring;
    }

}