<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportExcel extends SpotOnReport {
    
    
    function __construct() {
        parent::__construct();
    }

    function excel(){
        
        $_get = $this->input->get();
        
        $data = $this->getData();
        $genBy = $_get["genReportBy"];
        
        
        if($genBy == 1){
            $type = "tmn";
        } else if($genBy == 2){
            $type = "media";
        }
        
        $dataGroupBy = array();
        foreach ($data["data"] as $value) {
//            $valuePrint = $this->setDataBeforePrint($value);
            $value->duration = $this->getStringFormDuration(floor($value->duration / 1000));
            $arr = array($value);
            $valueFromArray = $this->getValueFromArray($dataGroupBy, $value->{$type."_ID"});
            $array = $this->getArray($valueFromArray);
            $dataGroupBy[$value->{$type."_ID"}] = array_merge($array, $arr);
        }

//        $this->load->view('pdf/header');
        $count = 0;
        $company = $this->m->getCpnById($this->cpnId);
        foreach ($company as $value) {
            $companyName = $value->cpn_name;
            $companyLink = $this->getValueFromObj($value, "cpn_link");
        }
        
        $csvData = array();
        
        foreach ($dataGroupBy as $key => $value) {

            $obj = $value[0];
            $name = $this->nullToZero($obj->{$type."_name"}, "NULL");
            $groupName = $this->nullToZero($obj->{"tmn_grp_name"}, "NULL");
            $valuePrint = array();
            $valuePrint["data"] = $value;
            $valuePrint["groupName"] = $groupName;
            $valuePrint[$type."Name"] = $name;
            $valuePrint["companyName"] = $companyName;
            $valuePrint["fromDate"] = $_get["fromDate"];
            $valuePrint["toDate"] = $_get["toDate"];
//            $valuePrint["countPlayer"] = 100;
//            $valuePrint["sumDuration"] = 123;
            $valuePrint["companyLink"] = $this->nullToZero($companyLink, "");;

            
            if($genBy == 1 ){
                $this->countPlayerAndsumDuration($valuePrint);
                array_merge($csvData, $this->headerPlayer($valuePrint, $key));
                array_merge($csvData, $this->tablePlayer($valuePrint));
            }else{
                $this->countPlayerAndsumDuration($valuePrint);
//                $this->countPlayerAndsumDuration($valuePrint, "media_ID");
                array_merge($csvData, $this->headerMedia($valuePrint, $key));
                array_merge($csvData, $this->tableMedia($valuePrint));
            }
        }
        
        $this->array_to_csv_download2($csvData, "report.csv" );
        
        
    } 
    
    private function headerMedia($valuePrint, $key){
        
        $ret = array();
        
        
        array_push($ret, array("Company Name", "Media", "From", "To", "Total Player ", "Total Duration "));
        
        array_push($ret,
            array($valuePrint["companyName"], 
                $valuePrint["mediaName"], 
                $valuePrint["fromDate"], 
                $valuePrint["toDate"], 
                count($this->countMedia[$key]), 
                $this->getStringFormDuration($this->sumDurationMedia[$key]))
        );
        
        return $ret;
    }
    
    private function headerPlayer($valuePrint, $key){
        
        $ret = array();
        
        array_push($ret, array("CompanyName", 
            "Player", 
            "Player Group", 
            "From", "To", 
            "Total Media ", 
            "Total Duration "));
        
        
        array_push($ret, array($valuePrint["companyName"], 
            $valuePrint["tmnName"], 
            $valuePrint["groupName"], 
            $valuePrint["fromDate"], 
            $valuePrint["toDate"], 
            count($this->countPlayer[$key]), 
            $this->getStringFormDuration($this->sumDurationGroup[$key])));
        
        return $ret;
    }
    
    private function tableMedia($valuePrint){

        $ret = array();
        
        
        array_push($ret, array("ID", "Player Group", "Player", 
            "Play Date", "Start Time", 
            "Stop Time", "Duration", 
            "PlayList", "Zone", "Story"));
        
        $id = 1;
        foreach ($valuePrint["data"] as $data) {
            array_push($ret, array($id, $data->tmn_grp_name, 
                $data->tmn_name, date("d/m/Y", strtotime($data->start_date)), 
                $data->start_time, $data->stop_time, 
                $data->duration, $data->pl_name, 
                $data->dsp_name, $data->story_name));
            $id++;
        }
        
        return $ret;
    }
    
    private function tablePlayer($valuePrint){

        $ret = array();
        
        array_push($ret, array("ID", "Media", 
            "Play Date", "Start Time", 
            "Stop Time", "Duration", 
            "PlayList", "Zone", "Story"));
        
        $id = 1;
        foreach ($valuePrint["data"] as $data) {
            array_push($ret, array($id, $data->media_name, 
                date("d/m/Y", strtotime($data->start_date)), 
                $data->start_time, $data->stop_time, 
                $data->duration, $data->pl_name, 
                $data->dsp_name, $data->story_name));
            $id++;
        }
        
        return $ret;
    }
    
    function index() {
        $this->excel();
        
    }
    
    function download() {
//        $this->genReport('F', "report.pdf");
        
        
        $data = $this->getData();
        
        
        $this->array_to_csv_download2($data, "numbers.csv" );
    }
//    
//    function array_to_csv_download($array, $filename = "export.csv", $delimiter=",") {
//        // open raw memory as file so no temp files needed, you might run out of memory though
//        $f = fopen('php://memory', 'w'); 
//        // loop over the input array
//        foreach ($array as $line) { 
//            // generate csv lines from the inner arrays
//            fputcsv($f, $line, $delimiter); 
//        }
//        // rewrind the "file" with the csv lines
//        fseek($f, 0);
//        // tell the browser it's going to be a csv file
//        header('Content-Type: application/csv');
//        // tell the browser we want to save it instead of displaying it
//        header('Content-Disposition: attachement; filename="'.$filename.'";');
//        // make php send the generated csv lines to the browser
//        fpassthru($f);
//    }
    
    function array_to_csv_download2($array, $filename = "export.csv", $delimiter=",") {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="'.$filename.'";');

        // open the "output" stream
        // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
        $f = fopen('php://output', 'w');

        foreach ($array as $line) {
            
            
            fputcsv($f, $line, $delimiter);
        }
    }   
}
