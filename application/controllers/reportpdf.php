<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ReportPDF extends SpotOnReport {

    private $fpdf = null;
    private $landscape = "P";
    function __construct() {
        parent::__construct();
        $this->load->library('loadfpdf');
        $this->fpdf = $this->loadfpdf->load();
    }

    function preview() {
        $this->genReport();
    }

    function download() {
        $this->genReport('F', "report.pdf");
    }

    private function genReport($options = 'I', $filename = "") {
        $_get = $this->input->get();

        $data = $this->getData();
        $genBy = $_get["genReportBy"];


        if ($genBy == 1) {
            $type = "tmn";
            $this->landscape = "P";
        } else if ($genBy == 2) {
            $type = "media";
            $this->landscape = "L";
        }

        $dataGroupBy = array();
        foreach ($data["data"] as $value) {
//            $valuePrint = $this->setDataBeforePrint($value);
            $value->duration = $this->getStringFormDuration(floor($value->duration / 1000));
            $arr = array($value);
            $valueFromArray = $this->getValueFromArray($dataGroupBy, $value->{$type . "_ID"});
            $array = $this->getArray($valueFromArray);
            $dataGroupBy[$value->{$type . "_ID"}] = array_merge($array, $arr);
        }

//        $this->load->view('pdf/header');
        $count = 0;
        $company = $this->m->getCpnById($this->cpnId);
        foreach ($company as $value) {
            $companyName = $value->cpn_name;
            $companyLink = $this->getValueFromObj($value, "cpn_link");
        }

        foreach ($dataGroupBy as $key => $value) {

            $obj = $value[0];
            $name = $this->nullToZero($obj->{$type . "_name"}, "NULL");
            $groupName = $this->nullToZero($obj->{"tmn_grp_name"}, "NULL");
            $valuePrint = array();
            $valuePrint["data"] = $value;
            $valuePrint["groupName"] = $groupName;
            $valuePrint[$type . "Name"] = $name;
            $valuePrint["companyName"] = $companyName;
            $valuePrint["fromDate"] = $_get["fromDate"];
            $valuePrint["toDate"] = $_get["toDate"];
            $valuePrint["companyLink"] = $this->nullToZero($companyLink, "");
            ;



            $this->fpdf->AddFont('angsa', '', 'angsa.php');

            $this->fpdf->SetFont('angsa', '', 12);

            $this->fpdf->AddPage($this->landscape);


            if ($genBy == 1) {
                $this->countPlayerAndsumDuration($valuePrint);
//                $this->countPlayerAndsumDuration($valuePrint, "tmn_grp_ID");
                $this->headerPlayer($valuePrint, $key);
                $this->tablePlayer($valuePrint);
            } else {
                $this->countPlayerAndsumDuration($valuePrint);
//                $this->countPlayerAndsumDuration($valuePrint, "media_ID");
                $this->headerMedia($valuePrint, $key);
                $this->tableMedia($valuePrint);
            }
        }

        $this->fpdf->Output("assets/upload/pdf/MyPDF.pdf", "I");
    }

    private function headerPlayer($valuePrint, $key) {
        //$this->fpdf->SetFillColor(210, 210, 210);

        $borderTableHeader = 0;
        $this->fpdf->Cell(30, 5, "CompanyName", $borderTableHeader);
        $this->fpdf->Cell(120, 5, $valuePrint["companyName"], $borderTableHeader);
        $this->fpdf->Cell(0, 5, "Playback Report by Player", $borderTableHeader, 1);

        $this->fpdf->Cell(30, 5, "Player", $borderTableHeader);
        $this->fpdf->Cell(0, 5, $valuePrint["tmnName"], $borderTableHeader, 1);

        $this->fpdf->Cell(30, 5, "Player Group", $borderTableHeader);
        $this->fpdf->Cell(0, 5, $valuePrint["groupName"], $borderTableHeader, 1);

        $this->fpdf->Cell(30, 5, "Period", $borderTableHeader);
        $this->fpdf->Cell(10, 5, "From", $borderTableHeader);
        $this->fpdf->Cell(30, 5, $valuePrint["fromDate"], $borderTableHeader);
        $this->fpdf->Cell(10, 5, "To", $borderTableHeader);

        $this->fpdf->Cell(30, 5, $valuePrint["toDate"], $borderTableHeader, 1);


        //ขีดเส้นใต้
        $this->fpdf->Cell(0, 5, "", "B", 1);
        $this->fpdf->Cell(0, 4, "", 0, 1);


        $this->fpdf->Cell(0, 5, "Summary", $borderTableHeader, 1);
        $this->fpdf->Cell(30, 5, "Total Media", $borderTableHeader);
        $this->fpdf->Cell(0, 5, count($this->countPlayer[$key]), $borderTableHeader, 1);
        $this->fpdf->Cell(30, 5, "Total Duration ", $borderTableHeader);
        $this->fpdf->Cell(0, 5, $this->getStringFormDuration($this->sumDurationGroup[$key]), $borderTableHeader, 1);

        //ขีดเส้นใต้
        $this->fpdf->Cell(0, 5, "", "B", 1);
        $this->fpdf->Cell(0, 4, "", 0, 1);
    }

    function tablePlayer($valuePrint) {
        $map = array(
            "media_name" => "media", 
            "start_date" => "Play Date", 
            "start_time" => "Start Time", 
            "stop_time" => "Stop Time", 
            "duration" => "Duration", 
            "pl_name" => "Playlist", 
            "dsp_name" => "Zone", 
            "story_name" => "Story");

//Header
        $w = array(8, //ID
            45, //media
            17, //Play Date
            15, //Start Time
            15, //Stop Time
            15, //Duration
            25, //Playlist
            25, //Zone
            25);//Story
        $align = array("C", "L", "C", "C", "C", "C", "L", "L", "L");

        //Colors, line width and bold font
        $this->fpdf->SetFillColor(210, 210, 210);
        $i = 1;
        $this->fpdf->Cell($w[0], 7, "ID", 1, 0, 'C', true);
        foreach ($map as $key => $value) {
            $this->fpdf->Cell($w[$i], 7, $value, 1, 0, 'C', true);
            $i++;
        }
        
        $this->fpdf->Ln();
//Color and font restoration
        $this->fpdf->SetFillColor(224, 235, 255);
//Data
        $fill = false;

        $i = 0; $count = 1;
        $x0 = $x = $this->fpdf->GetX();
        $y0 = $y = $this->fpdf->GetY();
        
        
        foreach ($valuePrint["data"] as $row) {
            if($count == 211){
                $count = 211;
            }
            
//            $y = $this->fpdf->GetY();
            if (floor($y) > 265 )
            {
                $this->fpdf->AddPage($this->landscape);
                $this->fpdf->SetFillColor(210, 210, 210);
                $i = 1;
                $this->fpdf->Cell($w[0], 7, "ID", 1, 0, 'C', true);
                foreach ($map as $key => $value) {
                    $this->fpdf->Cell($w[$i], 7, $value, 1, 0, 'C', true);
                    $i++;
                }

                $this->fpdf->Ln();
        //Color and font restoration
                $this->fpdf->SetFillColor(224, 235, 255);
                $y = $this->fpdf->GetY();
                //Header
//                $w = array(30, 30, 55, 25, 20, 20);
                //Header
//                for ($i = 0; $i < count($header); $i++)
//                   $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
    //            $this->Ln();
            } 
//            $this->Ln();
        
//            for ($i = 0; $i < 6; $i++) { //Avoid very lengthy texts
//                $row[$i] = substr($row[$i], 0, 160);
//            }
//
            //height of the row
            $yH = 5;
            $i = 0;
            $this->fpdf->SetXY($x, $y);
            $this->fpdf->MultiCell($w[0], $yH, $count, 0, 'C');
                
            $xt = 0; $yt = 0;
            foreach ($map as $key => $value) {
                $x = $x + $w[$i];
                $this->fpdf->SetXY($x, $y);
                $column = $row->$key;
                if($key == "start_date"){
                    $column = date("d/m/Y", strtotime($column));
                }
                        
                $this->fpdf->MultiCell($w[$i+1], $yH, $column, 0, $align[$i+1]);
                
                $xx = $this->fpdf->GetX();
                $yy = $this->fpdf->GetY();
                
                if($xx > $xt){
                    $xt = $xx;
                } 
                if($yy > $yt) $yt = $yy;
//                $this->fpdf->SetXY($x, $y);
//                $this->fpdf->Cell($xx, $yy - $y, "", 'LRB', 0, '', $fill);
                $i++;
            }
            $count++;  
            $x = $x0;
            $i = 0;
           
            $this->fpdf->SetXY($x, $y);
//            $this->fpdf->MultiCell($w[0], 5, $count, 0, 'C');
            $this->fpdf->Cell($w[0], $yt - $y, "", 'LRB', 0, '', $fill);
            
            
            foreach ($map as $key => $value) {
                $x = $x + $w[$i];
                $this->fpdf->SetXY($x, $y);
                $this->fpdf->Cell($w[$i+1], $yt - $y, "", 'LRB', 0, '', $fill); 
                $i++;
            }
            $y = $yt;
            $x = $x0;
        }
    }

    
    private function headerMedia($valuePrint, $key) {
        //$this->fpdf->SetFillColor(210, 210, 210);

        $borderTableHeader = 0;
        $this->fpdf->Cell(30, 5, "CompanyName", $borderTableHeader);
        $this->fpdf->Cell(215, 5, $valuePrint["companyName"], $borderTableHeader);
        $this->fpdf->Cell(0, 5, "Playback Report by Media", $borderTableHeader, 1);

        $this->fpdf->Cell(30, 5, "Media", $borderTableHeader);
        $this->fpdf->Cell(0, 5, $valuePrint["mediaName"], $borderTableHeader, 1);

        $this->fpdf->Cell(30, 5, "Period", $borderTableHeader);
        $this->fpdf->Cell(10, 5, "From", $borderTableHeader);
        $this->fpdf->Cell(30, 5, $valuePrint["fromDate"], $borderTableHeader);
        $this->fpdf->Cell(10, 5, "To", $borderTableHeader);
        $this->fpdf->Cell(30, 5, $valuePrint["toDate"], $borderTableHeader, 1);


        //ขีดเส้นใต้
        $this->fpdf->Cell(0, 5, "", "B", 1);
        $this->fpdf->Cell(0, 4, "", 0, 1);


        $this->fpdf->Cell(0, 5, "Summary", $borderTableHeader, 1);
        $this->fpdf->Cell(30, 5, "Total Player", $borderTableHeader);
        $this->fpdf->Cell(0, 5, count($this->countMedia[$key]), $borderTableHeader, 1);
        $this->fpdf->Cell(30, 5, "Total Duration ", $borderTableHeader);
        $this->fpdf->Cell(0, 5, $this->getStringFormDuration($this->sumDurationMedia[$key]), $borderTableHeader, 1);

        //ขีดเส้นใต้
        $this->fpdf->Cell(0, 5, "", "B", 1);
        $this->fpdf->Cell(0, 4, "", 0, 1);
        
    }

    function tableMedia($valuePrint) {
        $map = array(
            "tmn_name" => "Player", 
            "tmn_grp_name" => "Group Player", 
            "start_date" => "Play Date", 
            "start_time" => "Start Time", 
            "stop_time" => "Stop Time",
            "duration" => "Duration", 
            "pl_name" => "Playlist", 
            "dsp_name" => "Zone", 
            "story_name" => "Story");
        
            
//Header
        $w = array(8, //ID
            50, //Player
            50, //Group Player
            19, //Play Date
            15, //Start Time
            15, //Stop Time
            15, //Duration
            35, //Playlist
            35, //Zone
            35);//Story
        $align = array("C", "L", "L", "C", "C", "C", "C", "L", "L", "L");

        //Colors, line width and bold font
        $this->fpdf->SetFillColor(210, 210, 210);
        $i = 1;
        $this->fpdf->Cell($w[0], 7, "ID", 1, 0, 'C', true);
        foreach ($map as $key => $value) {
            $this->fpdf->Cell($w[$i], 7, $value, 1, 0, 'C', true);
            $i++;
        }
        
        $this->fpdf->Ln();
//Color and font restoration
        $this->fpdf->SetFillColor(224, 235, 255);
//Data
        $fill = false;

        $i = 0; $count = 1;
        $x0 = $x = $this->fpdf->GetX();
        $y0 = $y = $this->fpdf->GetY();
        
        
        foreach ($valuePrint["data"] as $row) {
            if($count == 14){
                $count = 14;
            }
            
//            $y = $this->fpdf->GetY();
            if (floor($y) > 180 )
            {
                $this->fpdf->AddPage($this->landscape);
                $this->fpdf->SetFillColor(210, 210, 210);
                $i = 1;
                $this->fpdf->Cell($w[0], 7, "ID", 1, 0, 'C', true);
                foreach ($map as $key => $value) {
                    $this->fpdf->Cell($w[$i], 7, $value, 1, 0, 'C', true);
                    $i++;
                }

                $this->fpdf->Ln();
        //Color and font restoration
                $this->fpdf->SetFillColor(224, 235, 255);
                $y = $this->fpdf->GetY();
                //Header
//                $w = array(30, 30, 55, 25, 20, 20);
                //Header
//                for ($i = 0; $i < count($header); $i++)
//                   $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
    //            $this->Ln();
            } 
//            $this->Ln();
        
//            for ($i = 0; $i < 6; $i++) { //Avoid very lengthy texts
//                $row[$i] = substr($row[$i], 0, 160);
//            }
//
            //height of the row
            $yH = 5;
            $i = 0;
            $this->fpdf->SetXY($x, $y);
            $this->fpdf->MultiCell($w[0], $yH, $count, 0, 'C');
                
            $xt = 0; $yt = 0;
            foreach ($map as $key => $value) {
                $x = $x + $w[$i];
                $this->fpdf->SetXY($x, $y);
                $column = $row->$key;
                if($key == "start_date"){
                    $column = date("d/m/Y", strtotime($column));
                }
                        
                $this->fpdf->MultiCell($w[$i+1], $yH, $column, 0, $align[$i+1]);
                
                $xx = $this->fpdf->GetX();
                $yy = $this->fpdf->GetY();
                
                if($xx > $xt){
                    $xt = $xx;
                } 
                if($yy > $yt) $yt = $yy;
//                $this->fpdf->SetXY($x, $y);
//                $this->fpdf->Cell($xx, $yy - $y, "", 'LRB', 0, '', $fill);
                $i++;
            }
            $count++;  
            $x = $x0;
            $i = 0;
           
            $this->fpdf->SetXY($x, $y);
//            $this->fpdf->MultiCell($w[0], 5, $count, 0, 'C');
            $this->fpdf->Cell($w[0], $yt - $y, "", 'LRB', 0, '', $fill);
            
            
            foreach ($map as $key => $value) {
                $x = $x + $w[$i];
                $this->fpdf->SetXY($x, $y);
                $this->fpdf->Cell($w[$i+1], $yt - $y, "", 'LRB', 0, '', $fill); 
                $i++;
            }
            $y = $yt;
            $x = $x0;
        }
    }
}
