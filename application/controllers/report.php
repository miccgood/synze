<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report extends SpotOnReport {
    private $countPlayer = array();
    private $countMedia = array();
    private $sumDurationMedia = array();
    private $sumDurationGroup = array();  
    
    function __construct() {
        parent::__construct();
    }

    function getData(){
        $_get = $this->input->get();
        
        $data["data"] = $this->m->getDataForReport($_get);
//        $this->countPlayerAndsumDuration($data);
        return $data;
    } 
    
    private function genReport($options = '', $filename = ""){
        $_get = $this->input->get();
        
        
        ini_set("memory_limit", "512M"); 
        
        $data = $this->getData();
        
//        $this->countPlayerAndsumDuration($data);
        $genBy = $_get["genReportBy"];
        
        
        if($genBy == 1){
            $type = "tmn";
        } else if($genBy == 2){
            $type = "media";
        } else {
            $type = "error";
        }
        
        $dataGroupBy = array();
        foreach ($data["data"] as $value) {
            
            $arrMedia = $this->getValueFromObj($value, "media_ID");
//            $arrTmnGrp = $this->getValueFromObj($value, "tmn_grp_ID");
            $arrTmn = $this->getValueFromObj($value, "tmn_ID");
            $this->countPlayer[$arrTmn][$arrMedia] = $this->nullToZero($this->getValueFromArray($this->getValueFromArray($this->countPlayer, $arrTmn), $arrMedia), 0)  + 1;
            $this->countMedia[$arrMedia][$arrTmn] = $this->nullToZero($this->getValueFromArray($this->getValueFromArray($this->countMedia, $arrMedia), $arrTmn), 0) + 1;
            $duration = $this->getDurationFormString( floor($value->duration / 1000));
            $this->sumDurationMedia[$arrMedia] = floor($this->nullToZero($this->getValueFromArray($this->sumDurationMedia, $arrMedia), 0) + $duration);
            $this->sumDurationGroup[$arrTmn] = floor($this->nullToZero($this->getValueFromArray($this->sumDurationGroup, $arrTmn), 0) + $duration);
            
            
            
//            $valuePrint = $this->setDataBeforePrint($value);
            $value->duration = $this->getStringFormDuration(floor($value->duration / 1000));
            $arr = array($value);
            $valueFromArray = $this->getValueFromArray($dataGroupBy, $value->{$type."_ID"});
            $array = $this->getArray($valueFromArray);
            $dataGroupBy[$value->{$type."_ID"}] = array_merge($array, $arr);
        }

        $lib = "pdf";
        $this->load->view($lib.'/header');
        $count = 0;
        $company = $this->m->getCpnById($this->cpnId);
        foreach ($company as $value) {
            $companyName = $value->cpn_name;
            $companyLink = $this->getValueFromObj($value, "cpn_link");
        }
        
        foreach ($dataGroupBy as $key => $value) {

            $obj = $value[0];
            $name = $this->nullToZero($obj->{$type."_name"}, "NULL");
            $playerGroup = $this->nullToZero($this->getValueFromObj($obj, "tmn_grp_name"), "NULL");
            $valuePrint = array();
            $valuePrint["data"] = $value;
            $valuePrint[$type."Name"] = $name;
            $valuePrint["playerGroup"] = $playerGroup;
            $valuePrint["companyName"] = $companyName;
            $valuePrint["fromDate"] = $_get["fromDate"];
            $valuePrint["toDate"] = $_get["toDate"];
            
            $valuePrint["countPlayer"] = count($this->countPlayer[$this->getValueFromObj($obj, "tmn_ID")]);
            $valuePrint["sumDurationPlayer"] = $this->getStringFormDuration($this->getValueFromArray($this->sumDurationGroup, $key));
            $valuePrint["countMedia"] = count($this->countMedia[$this->getValueFromObj($obj, "media_ID")]);
            $valuePrint["sumDurationMedia"] = $this->getStringFormDuration($this->getValueFromArray($this->sumDurationMedia, $key));
            
            $valuePrint["companyLink"] = $this->nullToZero($companyLink, "");
            
            $page = ($genBy == 1 ? "player" : "media");
            $this->load->view($lib.'/'. $page, $valuePrint);
            if($count < count($dataGroupBy) - 1){
                $this->load->view($lib.'/new_page');
                $count++;
            }
            
        }
//            $this->load->view('pdf/test');
        $this->load->view($lib.'/footer');
//        
//        
//		// Get output html
//        
//
//
        $html = $this->output->get_output();
//        
//        
        $this->load->library('pdf');
        $mpdf = $this->pdf->load(); 
//        $mpdf = new mPDF('c', 'A4-L');
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->list_indent_first_level = 0; 
        $mpdf->setAutoFont();

        $mpdf->writeHTML($html);
        $mpdf->Output($filename, $options);
        
        
//        $this->load->library('loadfpdf');
//        $fpdf = $this->loadfpdf->load(); 
//        
//        $fpdf->AddPage("L");
//        $fpdf->SetFont('Arial', '', 12);
//
//        $fpdf->WriteHTML($html);
//        $fpdf->Output();
        
        
//        $fpdf->addPage('L');
////        $fpdf = $pdf->setSourceFile('template.pdf');
////        $fpdf = $pdf->importPage(1); 
////        $fpdf->useTemplate($tplIdx); 
//        $fpdf->SetFont('Arial'); 
//        $fpdf->SetTextColor(255,0,0); 
//        $fpdf->SetXY(25, 25); 
//        $fpdf->Write(0, "This is just a test"); 
//        $fpdf->Output('newpdf.pdf', 'F');
        
        
        
        
//$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 
//$mpdf->SetDisplayMode('fullpage');
//$mpdf->list_indent_first_level = 0; 
//$stylesheet = file_get_contents('mpdfstyletables.css');
//$mpdf->WriteHTML($stylesheet,1);
//$mpdf->WriteHTML($html);
//$mpdf->Output('mpdf.pdf');



//            $pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="http://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley"> 
//            $pdf->WriteHTML($html); // write the HTML into the PDF
//            $pdf->Output($pdfFilePath, 'F'); // save to file because we can
//        }

//        redirect("/downloads/reports/$filename.pdf");   

    }
    
    private function countPlayerAndsumDuration($valuePrint) {
        
        foreach ($valuePrint["data"] as $value) {
            $arrMedia = $this->getValueFromObj($value, "media_ID");
//            $arrTmnGrp = $this->getValueFromObj($value, "tmn_grp_ID");
            $arrTmn = $this->getValueFromObj($value, "tmn_ID");
            $this->countPlayer[$arrTmn][$arrMedia] = $this->nullToZero($this->getValueFromArray($this->getValueFromArray($this->countPlayer, $arrTmn), $arrMedia), 0)  + 1;
            $this->countMedia[$arrMedia][$arrTmn] = $this->nullToZero($this->getValueFromArray($this->getValueFromArray($this->countMedia, $arrMedia), $arrTmn), 0) + 1;
            $duration = $this->getDurationFormString($value->duration);
            $this->sumDurationMedia[$arrMedia] = $this->nullToZero($this->getValueFromArray($this->sumDurationMedia, $arrMedia), 0) + $duration;
            $this->sumDurationGroup[$arrTmn] = $this->nullToZero($this->getValueFromArray($this->sumDurationGroup, $arrTmn), 0) + $duration;
        }
//        $valuePrint["countPlayer"] = 100;
//        $valuePrint["sumDuration"] = 123;
    }
    function putChar($str,$wha,$cnt) {
        if(strlen($str) <= $cnt){
            return $str;
        }
        $strip = false;
        if (strlen($str) % $cnt == 0) {
          $strip = true;
        }
        $tmp = preg_replace('/(.{'.$cnt.'})/',"$1$wha", $str);
        if ($strip) {
          $tmp = substr($tmp,0,-1);
        }
        return $tmp;
    }
           
    private function getSheetName($name){
//        $ext = pathinfo($name, PATHINFO_EXTENSION);
//        $name = str_replace($ext, "", $name);
//        
        $length = strlen($name) ;
        return ($length > 31 ? substr($name, 0, 31) : $name);
    }
            
    function preview(){
        $this->genReport();
    }  
    
    function excel(){
        ini_set("memory_limit", "512M");
        //load our new PHPExcel library
        $this->load->library('phpexcel');
        

        $filename='report.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        
        
        
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
            $value->duration = $this->getStringFormDuration($value->duration);
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

            
//            $this->load->view('pdf/'. $page, $valuePrint);
//            if($count < count($dataGroupBy) - 1){
////                $this->load->view('pdf/new_page');
//                $count++;
//            }
//            $sheetId = 1;
            $this->phpexcel->createSheet(NULL, $count);
            $this->phpexcel->setActiveSheetIndex($count);
//            $this->phpexcel->getActiveSheet()->setTitle($index);
            //activate worksheet number 1
//            $this->phpexcel->setActiveSheetIndexByName("momo");
//            $this->phpexcel->setActiveSheetIndex($count);
            //name the worksheet
            $workSheet = $this->phpexcel->getActiveSheet();
            //set ชื่อ sheet
//            $workSheet->setTitle("test");
            if($genBy == 1 ){
                $this->countPlayerAndsumDuration($valuePrint);
//                $this->countPlayerAndsumDuration($valuePrint, "tmn_grp_ID");
                $this->headerPlayer($workSheet,$valuePrint, $key);
                $this->tablePlayer($workSheet,$valuePrint);
            }else{
                $this->countPlayerAndsumDuration($valuePrint);
//                $this->countPlayerAndsumDuration($valuePrint, "media_ID");
                $this->headerMedia($workSheet, $valuePrint, $key);
                $this->tableMedia($workSheet,$valuePrint);
            }
            
            $count++;
            //change the font size
//            $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
            //make the font become bold
//            $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            //merge cell A1 until D1
//            $this->phpexcel->getActiveSheet()->mergeCells('A1:D1');
            //set aligment to center for that merged cell (A1 to D1)
//            $this->phpexcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        }
        
        
        
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    } 
    
    private function headerMedia($workSheet, $valuePrint, $key){
        
        $workSheet->getColumnDimension('A')->setWidth(5);
        $workSheet->getColumnDimension('B')->setWidth(13);
        $workSheet->getColumnDimension('C')->setWidth(8);
        $workSheet->getColumnDimension('D')->setWidth(10);
        $workSheet->getColumnDimension('E')->setWidth(10);
        $workSheet->getColumnDimension('F')->setWidth(11);
        $workSheet->getColumnDimension('G')->setWidth(10);
        
        
        $workSheet->getColumnDimension('J')->setWidth(15);
        $workSheet->getColumnDimension('K')->setWidth(15);
        $workSheet->getColumnDimension('L')->setWidth(15);
        
        $workSheet->setTitle($this->getSheetName($valuePrint["mediaName"]));
        
//        $workSheet->mergeCells('A1:B4')->getStyle(
//            'A1:B4'
//        )->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//        
//        $image = $valuePrint['companyLink'];//'./theme/images/add_media_to_playlist.png';
        
//        if(file_exists($image)){
//            $objDrawing = new PHPExcel_Worksheet_Drawing();
//            $objDrawing->setName('Logo');
//            $objDrawing->setDescription('Logo');
//            $objDrawing->setPath($image);       // filesystem reference for the image file
////          $objDrawing->setHeight(150);                 // sets the image height to 36px (overriding the actual image height); 
//            $objDrawing->setWidth(130); 
//            $objDrawing->setCoordinates('A1');    // pins the top-left corner of the image to cell D24
//            $objDrawing->setOffsetX(0);                // pins the top left corner of the image at an offset of 10 points horizontally to the right of the top-left corner of the cell
//            $objDrawing->setWorksheet($workSheet);
//        }

        
        //set cell A1 content with some text
        $workSheet->setCellValue('A1', "Company Name");
        $workSheet->getStyle('A1')->getFont()->setBold(true);
        
        $workSheet->setCellValue('C1', $valuePrint["companyName"]);
        $workSheet->getStyle('C1')->getFont()->setBold(true);
        
        $workSheet->setCellValue('K1', "Playback Report by Player");
        $workSheet->getStyle('K1')->getFont()->setBold(true);

        $workSheet->setCellValue('A3', "Media");
        $workSheet->getStyle('A3')->getFont()->setBold(true);
        
        $workSheet->setCellValue('C3', $valuePrint["mediaName"]);
        $workSheet->setCellValue('A4', "Period");
        $workSheet->getStyle('A4')->getFont()->setBold(true);
        
        $workSheet->setCellValue('C4', "From");
        $workSheet->getStyle('C4')->getFont()->setBold(true);
        $workSheet->getStyle('C4')->applyFromArray(
                    array(
                        'alignment' => array(
                                'wrap'       => true,
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                            )
                    )
                );
        
        $workSheet->setCellValue('D4', $valuePrint["fromDate"]);
        
        $workSheet->setCellValue('F4', "To");
        $workSheet->getStyle('F4')->getFont()->setBold(true);
        $workSheet->getStyle('F4')->applyFromArray(
                    array(
                        'alignment' => array(
                                'wrap'       => true,
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                            )
                    )
                );
        
        $workSheet->setCellValue('G4',  $valuePrint["toDate"]);
        
        $workSheet->mergeCells('A6:L6')->getStyle(
            'A6:L6'
        )->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
        $workSheet->setCellValue('A7', "Summary ");
        $workSheet->getStyle('A7')->getFont()->setBold(true);
        $workSheet->setCellValue('A8', "Total Player ");
        $workSheet->setCellValue('A9', "Total Duration ");
        $workSheet->setCellValue('C8', count($this->countMedia[$key]));
        $workSheet->setCellValue('C9', $this->getStringFormDuration($this->sumDurationMedia[$key]));
        
        $workSheet->mergeCells('A11:L11')->getStyle(
            'A11:L11'
        )->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
    }
    
    private function headerPlayer($workSheet, $valuePrint, $key){
        $workSheet->getColumnDimension('A')->setWidth(5);
        $workSheet->getColumnDimension('B')->setWidth(10);
        $workSheet->getColumnDimension('C')->setWidth(7);
        $workSheet->getColumnDimension('D')->setWidth(10);
        $workSheet->getColumnDimension('E')->setWidth(10);
        $workSheet->getColumnDimension('F')->setWidth(10);
        $workSheet->getColumnDimension('G')->setWidth(11);

        
        $workSheet->getColumnDimension('K')->setWidth(15);
        $workSheet->getColumnDimension('L')->setWidth(15);
        $workSheet->getColumnDimension('M')->setWidth(15);
        
        $workSheet->setTitle($this->getSheetName($valuePrint["tmnName"]));
        
//        $workSheet->mergeCells('A1:B4')->getStyle(
//            'A1:B4'
//        )->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//        
//        $image = $valuePrint['companyLink'];//'./theme/images/add_media_to_playlist.png';
//        
//        if(file_exists($image)){
//            $objDrawing = new PHPExcel_Worksheet_Drawing();
//            $objDrawing->setName('Logo');
//            $objDrawing->setDescription('Logo');
//            $objDrawing->setPath($image);       // filesystem reference for the image file
////          $objDrawing->setHeight(150);                 // sets the image height to 36px (overriding the actual image height); 
//            $objDrawing->setWidth(130); 
//            $objDrawing->setCoordinates('A1');    // pins the top-left corner of the image to cell D24
//            $objDrawing->setOffsetX(0);                // pins the top left corner of the image at an offset of 10 points horizontally to the right of the top-left corner of the cell
//            $objDrawing->setWorksheet($workSheet);
//        }
        
        $workSheet->setCellValue('A1', "CompanyName");
        $workSheet->getStyle('A1')->getFont()->setBold(true);
        //set cell A1 content with some text
        $workSheet->setCellValue('C1', $valuePrint["companyName"]);
//        $workSheet->getStyle('C1')->getFont()->setBold(true);
        
        $workSheet->setCellValue('L1', "Playback Report by Player");
        $workSheet->getStyle('L1')->getFont()->setBold(true);

        $workSheet->setCellValue('A3', "Player");
        $workSheet->getStyle('A3')->getFont()->setBold(true);
        $workSheet->setCellValue('C3', $valuePrint["tmnName"]);
        
        $workSheet->setCellValue('A4', "Player Group");
        $workSheet->getStyle('A4')->getFont()->setBold(true);
        $workSheet->setCellValue('C4', $valuePrint["groupName"]);
        
        
        $workSheet->setCellValue('A5', "Period");
        $workSheet->getStyle('A5')->getFont()->setBold(true);
        
        $workSheet->setCellValue('C5', "From");
        $workSheet->getStyle('C5')->getFont()->setBold(true);
        $workSheet->getStyle('C5')->applyFromArray(
                    array(
                        'alignment' => array(
                                'wrap'       => true,
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                            )
                    )
                );
        
        $workSheet->setCellValue('D5', $valuePrint["fromDate"]);
        
        $workSheet->setCellValue('F5', "To");
        $workSheet->getStyle('F5')->getFont()->setBold(true);
        $workSheet->getStyle('F5')->applyFromArray(
                    array(
                        'alignment' => array(
                                'wrap'       => true,
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                            )
                    )
                );
        $workSheet->setCellValue('G5',  $valuePrint["toDate"]);
        
        $workSheet->mergeCells('A7:M7')->getStyle(
            'A7:M7'
        )->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
        $workSheet->setCellValue('A8', "Summary ");
        $workSheet->getStyle('A8')->getFont()->setBold(true);
        $workSheet->setCellValue('A9', "Total Media ");
        $workSheet->setCellValue('A10', "Total Duration ");
        $workSheet->setCellValue('C9', count($this->countPlayer[$key]));
        $workSheet->setCellValue('C10', $this->getStringFormDuration($this->sumDurationGroup[$key]));
        
        $workSheet->mergeCells('A12:M12')->getStyle(
            'A12:M12'
        )->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    }
    
    private function tableMedia($workSheet, $valuePrint){
        //header 
        $workSheet->mergeCells('J12:L12');
               $workSheet->setCellValue('J12', "Reference");
        
        $workSheet->setCellValue('A13', "ID");
        $workSheet->setCellValue('B13', "Player Group");
        $workSheet->mergeCells('C13:E13');
               $workSheet->setCellValue('C13', "Player");
        $workSheet->setCellValue('F13', "Play Date");       
        $workSheet->setCellValue('G13', "Start Time");
        $workSheet->setCellValue('H13', "Stop Time");
        $workSheet->setCellValue('I13', "Duration");
        $workSheet->setCellValue('J13', "PlayList");
        $workSheet->setCellValue('K13', "Zone");
        $workSheet->setCellValue('L13', "Story");
        
        //---//
        $workSheet->getStyle('J12:L12')->getFont()->setBold(true);
        $workSheet->getStyle('J12:L12')->getBorders()->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $workSheet->getStyle('J12:L12')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'EEEEEE')
                        )
                    )
                );
        $workSheet->getStyle('J12:L12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //---//
        //set ตัวหนา
        $workSheet->getStyle('A13:L13')->getFont()->setBold(true);
        //set เส้นขอบ
        $workSheet->getStyle('A13:L13')->getBorders()->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //ใส่สี จัดกลาง
        $workSheet->getStyle('A13:L13')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'EEEEEE'),
                        'alignment' => array(
                                        'wrap'       => true,
                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                    )
                        )
                    )
                );
        $workSheet->getStyle('A13:L13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $row = 14;
        $id = 1;
        foreach ($valuePrint["data"] as $data) {
            $workSheet->setCellValue('A'.$row, $id);
            $workSheet->setCellValue('B'.$row, $data->tmn_grp_name);
            $workSheet->mergeCells('C'.$row.':E'.$row);
                   $workSheet->setCellValue('C'.$row, $data->tmn_name);
            $workSheet->setCellValue('F'.$row, date("d/m/Y", strtotime($data->start_date)));
            $workSheet->setCellValue('G'.$row, $data->start_time);
            $workSheet->setCellValue('H'.$row, $data->stop_time);
            $workSheet->setCellValue('I'.$row, $data->duration);
            $workSheet->setCellValue('J'.$row, $data->pl_name);
            $workSheet->setCellValue('K'.$row, $data->dsp_name);
            $workSheet->setCellValue('L'.$row, $data->story_name);
            //set เส้นขอบ
            $workSheet->getStyle("A$row:L$row")->getBorders()->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            
            $workSheet->getStyle("A$row")->applyFromArray(
                    array(
                        'alignment' => array(
                                        'wrap'       => true,
                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                    )
                        
                    )
                );
        
            //ใส่สี จัดกลาง
            $workSheet->getStyle("F$row:I$row")->applyFromArray(
                    array(
                        'alignment' => array(
                                        'wrap'       => true,
                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                    )
                        
                    )
                );
        
            $row++;
            $id++;
        }
        
    }
    
    private function tablePlayer($workSheet, $valuePrint){
        //header 
        $workSheet->mergeCells('K13:M13');
               $workSheet->setCellValue('K13', "Reference");
        
        $workSheet->setCellValue('A14', "ID");
        
        $workSheet->mergeCells("B14:F14");
        $workSheet->setCellValue('B14', "Media");
//        $workSheet->mergeCells('C14:D14');
//               $workSheet->setCellValue('C14', "Start Time");
//               
////        $workSheet->setCellValue('F14', "Start Time");
//        $workSheet->mergeCells('E14:F14');
//               $workSheet->setCellValue('E14', "Stop Time");
        
        $workSheet->setCellValue('G14', "Play Date");    
        $workSheet->setCellValue('H14', "Start Time");
        $workSheet->setCellValue('I14', "Stop Time");
        $workSheet->setCellValue('J14', "Duration");
        $workSheet->setCellValue('K14', "PlayList");
        $workSheet->setCellValue('L14', "Zone");
        $workSheet->setCellValue('M14', "Story");
        
        //---//
        $workSheet->getStyle('K13:M13')->getFont()->setBold(true);
        $workSheet->getStyle('K13:M13')->getBorders()->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $workSheet->getStyle('K13:M13')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'EEEEEE')
                        )
                    )
                );
        $workSheet->getStyle('K13:M13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//        //---//
        //set ตัวหนา
        $workSheet->getStyle('A14:M14')->getFont()->setBold(true);
        //set เส้นขอบ
        $workSheet->getStyle('A14:M14')->getBorders()->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //ใส่สี จัดกลาง
        $workSheet->getStyle('A14:M14')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'EEEEEE'),
                        'alignment' => array(
                                        'wrap'       => true,
                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                    )
                        )
                    )
                );
        $workSheet->getStyle('A14:M14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//        
        $row = 15;
        $id = 1;
        foreach ($valuePrint["data"] as $data) {
            $workSheet->setCellValue('A'.$row, $id);
            $workSheet->mergeCells("B$row:F$row");
            $workSheet->setCellValue('B'.$row, $data->media_name);
            
//            $workSheet->mergeCells("C$row:D$row");
//               $workSheet->setCellValue("C$row", $data->start_time);
//               
////        $workSheet->setCellValue("F$row", "Start Time");
//            $workSheet->mergeCells("E$row:F$row");
//                $workSheet->setCellValue("E$row", $data->stop_time);
            $workSheet->setCellValue("G$row", date("d/m/Y", strtotime($data->start_date)));
            $workSheet->setCellValue("H$row", $data->start_time);
            $workSheet->setCellValue("I$row", $data->stop_time);
            $workSheet->setCellValue("J$row", $data->duration);
            $workSheet->setCellValue("K$row", $data->pl_name);
            $workSheet->setCellValue("L$row", $data->dsp_name);
            $workSheet->setCellValue("M$row", $data->story_name);
        
        
//            $workSheet->mergeCells('C'.$row.':E'.$row);
//                   $workSheet->setCellValue('C'.$row, $data->tmn_name);
//
//            $workSheet->setCellValue('F'.$row, $data->start_time);
//            $workSheet->setCellValue('G'.$row, $data->stop_time);
//            $workSheet->setCellValue('H'.$row, $data->duration);
//            $workSheet->setCellValue('I'.$row, $data->pl_name);
//            $workSheet->setCellValue('J'.$row, $data->dsp_name);
//            $workSheet->setCellValue('K'.$row, $data->story_name);
            //set เส้นขอบ
            $workSheet->getStyle("A$row:M$row")->getBorders()->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            $workSheet->getStyle("A$row")->applyFromArray(
                    array(
                        'alignment' => array(
                                        'wrap'       => true,
                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                    )
                        
                    )
                );
            //ใส่สี จัดกลาง
            $workSheet->getStyle("G$row:J$row")->applyFromArray(
                    array(
                        'alignment' => array(
                                        'wrap'       => true,
                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                    )
                        
                    )
                );
        
            $row++;
            $id++;
        }
        
    }
    
    function pdf(){
//        'F', $filename = "report.pdf"
        $this->genReport('D', "report.pdf");
//        echo json_encode("pdf");
    } 
    
    function index() {

        $data = $this->getDefaultValue();
        $this->crud->set_table('mst_tmn')
        ->set_subject('Monitor')
        ->set_relation("tmn_grp_ID", "mst_tmn_grp", "tmn_grp_name")
        ->columns('tmn_grp_ID', 'tmn_name', 'tmn_status_id')
            ->order_by("tmn_name", "DESC")
                
            ->display_as('tmn_grp_ID', 'Group Name')
            ->display_as('tmn_name', 'Name')
            ->display_as('tmn_desc', 'Description')
            ->display_as('tmn_os', 'OS')
              
            ->display_as('tmn_uuid', 'UUID')
            ->display_as('tmn_regis_date', 'Register Date')
                
            ->display_as('tmn_status_id', 'Status')
            ->display_as('tmn_status_message', 'Message')
            ->display_as('tmn_status_update', 'Status Update')
            
        
        ->callback_column("tmn_status_id", array($this, "_tmn_status_id"))
                
        ->set_default_value($data)
        ;

        $this->output();
        
    }

    private function getDefaultValue(){
        $data = array();
        $data["player"] = $this->m->getPlayerTemp();
        $data["playerGroup"] = $this->m->getPlayerGroupTemp();
        $data["media"] = $this->m->getMediaTemp();
        $mediaList = $data["media"];
        $count = count($mediaList);
        return $data;
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
            
            
        if($message == null){
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
//        $this->crud->unset_add()->unset_edit()->unset_delete();
    }
    
    private function getArray($input){
        return ($input == null) ? array() : $input;
    }
    
}