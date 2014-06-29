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
//                $this->countPlayerAndsumDuration($valuePrint, "tmn_grp_ID");
                $this->headerPlayer($valuePrint, $key);
                $this->tablePlayer($valuePrint);
            }else{
                $this->countPlayerAndsumDuration($valuePrint);
//                $this->countPlayerAndsumDuration($valuePrint, "media_ID");
                $this->headerMedia($valuePrint, $key);
                $this->tableMedia($valuePrint);
            }
        }
        
    } 
    
    private function headerMedia($valuePrint, $key){
        
        
        //set cell A1 content with some text
        $workSheet->setCellValue('A1', "Company Name");
        
        $workSheet->setCellValue('C1', $valuePrint["companyName"]);
        
//        $workSheet->setCellValue('K1', "Playback Report by Media");

        $workSheet->setCellValue('A3', "Media");
        
        $workSheet->setCellValue('C3', $valuePrint["mediaName"]);
        
        
//        $workSheet->setCellValue('A4', "Period");

        
        $workSheet->setCellValue('C4', "From");

        
        $workSheet->setCellValue('D4', $valuePrint["fromDate"]);
        
        $workSheet->setCellValue('F4', "To");

        $workSheet->setCellValue('G4',  $valuePrint["toDate"]);
        
        $workSheet->setCellValue('A8', "Total Player ");
        $workSheet->setCellValue('C8', count($this->countMedia[$key]));
        
        $workSheet->setCellValue('A9', "Total Duration ");
        $workSheet->setCellValue('C9', $this->getStringFormDuration($this->sumDurationMedia[$key]));
        
    }
    
    private function headerPlayer($workSheet, $valuePrint, $key){
        
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
