<?php

require('fpdf\fpdf.php');
define('FPDF_FONTPATH','fpdf\font');
class PDF extends FPDF {

//Load data
    function LoadData($file) {
        //Read file lines
        $lines = file($file);
        $data = array();
        foreach ($lines as $line)
            $data[] = explode(';', chop($line));
        return $data;
    }

    function headerTable($header){
        $y = $this->GetY();
        if (floor($y) > 265 )
        {
            $this->Ln();
            //Header
            $w = array(30, 30, 55, 25, 20, 20);
            //Header
            for ($i = 0; $i < count($header); $i++)
               $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
//            $this->Ln();
        } 
        $this->Ln();
    }
//Simple table
    function BasicTable($header, $data) {
        //Header
        $w = array(30, 30, 55, 25, 20, 20);
        //Header
        for ($i = 0; $i < count($header); $i++)
           $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
//        $this->Ln();
        //Data
        foreach ($data as $eachResult) {
            
            $this->headerTable($header);
            $this->Cell(30, 6, $eachResult["log_ID"], 1);
            $this->Cell(30, 6, $eachResult["tmn_grp_ID"], 1);
            $this->Cell(55, 6, $eachResult["tmn_grp_name"], 1);
            $this->Cell(25, 6, $eachResult["tmn_ID"], 1, 0, 'C');
            $this->Cell(20, 6, $eachResult["tmn_name"], 1);
            $this->Cell(20, 6, $eachResult["dpm_ID"], 1);
            
//             'log_ID',  'tmn_grp_ID',  'tmn_grp_name',  'tmn_ID',  'tmn_name',  'dpm_ID'
        }
    }

//Better table
    function ImprovedTable($header, $data) {
        //Column widths
        $w = array(20, 30, 55, 25, 25, 25);
        //Header
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
        $this->Ln();
        //Data

        foreach ($data as $eachResult) {

             $this->Cell(20, 6, iconv( 'UTF-8','TIS-620',$eachResult["log_ID"]), 1);
            $this->Cell(30, 6, iconv( 'UTF-8','TIS-620',$eachResult["tmn_grp_ID"]), 1);
            $this->Cell(55, 6, iconv( 'UTF-8','TIS-620',$eachResult["tmn_grp_name"]), 1);
            $this->Cell(25, 6, iconv( 'UTF-8','TIS-620',$eachResult["tmn_ID"]), 1, 0, 'C');
            $this->Cell(20, 6, iconv( 'UTF-8','TIS-620',$eachResult["tmn_name"]), 1);
            $this->Cell(20, 6, iconv( 'UTF-8','TIS-620',$eachResult["dpm_ID"]), 1);
            
//            $this->Cell(20, 6, $eachResult["tmn_grp_name"], 1);
//            $this->Cell(30, 6, $eachResult["tmn_name"], 1);
//            $this->Cell(55, 6, $eachResult["shd_name"], 1);
//            $this->Cell(25, 6, $eachResult["CountryCode"], 1, 0, 'C');
//            $this->Cell(25, 6, number_format($eachResult["Budget"], 2), 1, 0, 'R');
//            $this->Cell(25, 6, number_format($eachResult["Budget"], 2), 1, 0, 'R');
            $this->Ln();
        }





        //Closure line
        $this->Cell(array_sum($w), 0, '', 'T');
    }

//Colored table
    function FancyTable($header, $data) {
        //Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        //Header
        $w = array(20, 30, 55, 25, 25, 25);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        //Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        //Data
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row[2], 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, $row[3], 'LR', 0, 'C', $fill);
            $this->Cell($w[4], 6, number_format($row[4]), 'LR', 0, 'R', $fill);
            $this->Cell($w[5], 6, number_format($row[5]), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }

}

$pdf = new PDF();
//Column titles
$header = array( 'log_ID',  'tmn_grp_ID',  'tmn_grp_name',  'tmn_ID',  'tmn_name',  'dpm_ID' );//,  'shd_ID',  'shd_name',  'shd_start_date',  'shd_start_time',  'shd_stop_date',  'shd_stop_time',  'story_ID',  'story_name',  'lyt_id',  'lyt_name',  'dsp_ID',  'dsp_name',  'pl_ID',  'pl_name',  'media_ID',  'media_name');
//Data loading
//*** Load MySQL Data ***//
$objConnect = mysql_connect("localhost", "root", "root") or die("Error Connect to Database");
$objDB = mysql_select_db("821508_synze_test");
$strSQL = "SELECT * FROM log Limit 100";
$objQuery = mysql_query($strSQL);
$resultData = array();
for ($i = 0; $i < mysql_num_rows($objQuery); $i++) {
    $result = mysql_fetch_array($objQuery);
    array_push($resultData, $result);
}
//************************//



//$pdf->SetFont('Arial', '', 10);

$pdf->AddFont('angsa','','angsa.php');

$pdf->SetFont('angsa','',12);

//*** Table 1 ***//
$pdf->AddPage();
//$pdf->Image('logo.png', 80, 8, 33);
//$pdf->Ln(35);
$pdf->BasicTable($header, $resultData);

//*** Table 2 ***//
//$pdf->AddPage();
//$pdf->Image('logo.png', 80, 8, 33);
//$pdf->Ln(35);
//$pdf->ImprovedTable($header, $resultData);

//*** Table 3 ***//
//$pdf->AddPage();
//$pdf->Image('logo.png', 80, 8, 33);
//$pdf->Ln(35);
//$pdf->FancyTable($header, $resultData);
//$pdf->Cell(0,20,iconv( 'UTF-8','TIS-620','สวัสดี ชาวไทยครีเอท'),0,1,"C");
$pdf->Output("assets/upload/pdf/MyPDF.pdf", "I");
?>
