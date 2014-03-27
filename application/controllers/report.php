<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report extends SpotOnReport {
    private $countPlayer = array();
    private $countMedia = array();
    private $sumDurationMedia = array();
    private $sumDurationGroup = array();    
    function __construct() {
        parent::__construct();
    }
    
    function countTerminal($value, $row) {
        
        
        $E = @$this->db->query('select count(tmn_id) as result from mst_tmn where tmn_grp_ID = ?', array($row->tmn_grp_ID))->row()->result;
        return ($E) ? (string) $E : '0'; // or: return ($E) ? (string) $E : '~empty~';
    }

    function getData(){
        $_get = $this->input->get();
        
        $data["data"] = $this->m->getDataForReport($_get);
        
        return $data;
    } 
    
    private function genReport($options = array('Attachment'=>0)){
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
            $valuePrint = $this->setDataBeforePrint($value);
            $arr = array($valuePrint);
            $valueFromArray = $this->getValueFromArray($dataGroupBy, $value->{$type."_ID"});
            $array = $this->getArray($valueFromArray);
            $dataGroupBy[$value->{$type."_ID"}] = array_merge($array, $arr);
        }

        $this->load->view('pdf/header');
        $count = 0;
        $company = $this->m->getCpnById($this->cpnId);
        foreach ($company as $value) {
            $companyName = $value->cpn_name;
        }
        
        foreach ($dataGroupBy as $key => $value) {

            $obj = $value[0];
            $name = $this->nullToZero($obj->{$type."_name"}, "NULL");
            $valuePrint = array();
            $valuePrint["data"] = $value;
            $valuePrint[$type."Name"] = $name;
            $valuePrint["companyName"] = $companyName;
            $valuePrint["fromDate"] = $_get["fromDate"];
            $valuePrint["toDate"] = $_get["toDate"];
            
            $valuePrint["countPlayer"] = count($this->countPlayer);
            $valuePrint["sumDurationGroup"] = $this->sumDurationGroup[$key];
            $valuePrint["countMedia"] = count($this->countMedia);
            $valuePrint["sumDurationMedia"] = $this->sumDurationMedia[$key];
            
            $page = ($genBy == 1 ? "player" : "media");
            $this->load->view('pdf/'. $page, $valuePrint);
            if($count < count($dataGroupBy) - 1){
                $this->load->view('pdf/new_page');
                $count++;
            }
            
        }
//            $this->load->view('pdf/test');
        $this->load->view('pdf/footer');
        
        
		// Get output html
        
        ini_set("memory_limit", "128M"); 


        $html = $this->output->get_output();

        // Load library
        $this->load->library('dompdf_gen');

        // Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("report.pdf", $options);
    }
    
    private function countPlayerAndsumDuration($valuePrint, $index) {
        
        foreach ($valuePrint["data"] as $value) {
            $arrMedia = $this->getValueFromObj($value, "media_ID");
            $arrTmnGrp = $this->getValueFromObj($value, "tmn_grp_ID");
            $arrTmn = $this->getValueFromObj($value, "tmn_ID");
            $this->countPlayer[$arrMedia] = $this->nullToZero($this->getValueFromArray($this->countPlayer, $arrMedia), 0) + 1;
            $this->countMedia[$arrTmnGrp] = $this->nullToZero($this->getValueFromArray($this->countMedia, $arrTmnGrp), 0) + 1;
            $this->sumDurationMedia[$arrMedia] = $this->nullToZero($this->getValueFromArray($this->sumDurationMedia, $arrMedia), 0) + $value->duration;
            $this->sumDurationGroup[$arrTmn] = $this->nullToZero($this->getValueFromArray($this->sumDurationGroup, $arrTmn), 0) + $value->duration;
        }
//        $valuePrint["countPlayer"] = 100;
//        $valuePrint["sumDuration"] = 123;
    }
    function setDataBeforePrint($value){
        $value->pl_name = $this->putChar($value->pl_name, " ", 20);
        
        
        return $value;
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
            
            
    function preview(){
        
        $this->genReport(array('Attachment'=>0));
    }  
    
    function excel(){
        
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
            $valuePrint = $this->setDataBeforePrint($value);
            $arr = array($valuePrint);
            $valueFromArray = $this->getValueFromArray($dataGroupBy, $value->{$type."_ID"});
            $array = $this->getArray($valueFromArray);
            $dataGroupBy[$value->{$type."_ID"}] = array_merge($array, $arr);
        }

//        $this->load->view('pdf/header');
        $count = 0;
        $company = $this->m->getCpnById($this->cpnId);
        foreach ($company as $value) {
            $companyName = $value->cpn_name;
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
            $valuePrint["countPlayer"] = 100;
            $valuePrint["sumDuration"] = 123;

            
//            $this->load->view('pdf/'. $page, $valuePrint);
//            if($count < count($dataGroupBy) - 1){
////                $this->load->view('pdf/new_page');
//                $count++;
//            }
            $sheetId = 1;
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
                $this->countPlayerAndsumDuration($valuePrint, "tmn_grp_ID");
                $this->headerPlayer($workSheet,$valuePrint, $key);
                $this->tablePlayer($workSheet,$valuePrint);
            }else{
                $this->countPlayerAndsumDuration($valuePrint, "media_ID");
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
        $workSheet->getColumnDimension('C')->setWidth(5);
        $workSheet->getColumnDimension('D')->setWidth(13);
        $workSheet->getColumnDimension('E')->setWidth(6);
        $workSheet->getColumnDimension('F')->setWidth(10);
        $workSheet->getColumnDimension('G')->setWidth(10);
//        $workSheet->getColumnDimension('C')->setWidth(5);
//        $workSheet->getColumnDimension('D')->setWidth(5);
//        $workSheet->getColumnDimension('E')->setWidth(5);
//        $workSheet->getColumnDimension('F')->setWidth(5);
        
        $workSheet->setTitle($valuePrint["mediaName"]);
        
        $workSheet->mergeCells('A1:B4')->getStyle(
            'A1:B4'
        )->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
        
        //set cell A1 content with some text
        $workSheet->setCellValue('D1', $valuePrint["companyName"]);
        $workSheet->getStyle('D1')->getFont()->setBold(true);
        
        $workSheet->setCellValue('K1', "Playback Report by Player");
        $workSheet->getStyle('K1')->getFont()->setBold(true);

        $workSheet->setCellValue('D3', "Media");
        $workSheet->getStyle('D3')->getFont()->setBold(true);
        
        $workSheet->setCellValue('E3', $valuePrint["mediaName"]);
        $workSheet->setCellValue('D4', "Period");
        $workSheet->getStyle('D4')->getFont()->setBold(true);
        
        $workSheet->setCellValue('E4', "from");
        $workSheet->getStyle('E4')->getFont()->setBold(true);
        $workSheet->setCellValue('F4', $valuePrint["fromDate"]);
        $workSheet->setCellValue('H4', "to");
        $workSheet->getStyle('H4')->getFont()->setBold(true);
        $workSheet->setCellValue('I4',  $valuePrint["toDate"]);
        
        $workSheet->mergeCells('A6:M6')->getStyle(
            'A6:M6'
        )->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
        $workSheet->setCellValue('A7', "Summary ");
        $workSheet->getStyle('A7')->getFont()->setBold(true);
        $workSheet->setCellValue('A8', "Total Player ");
        $workSheet->setCellValue('A9', "Total Duration ");
        $workSheet->setCellValue('C8', count($this->countMedia));
        $workSheet->setCellValue('C9', $this->sumDurationMedia[$key]);
        
        $workSheet->mergeCells('A11:M11')->getStyle(
            'A11:M11'
        )->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
    }
    
    private function headerPlayer($workSheet, $valuePrint, $key){
        $workSheet->getColumnDimension('A')->setWidth(5);
        $workSheet->getColumnDimension('B')->setWidth(13);
        $workSheet->getColumnDimension('C')->setWidth(5);
        $workSheet->getColumnDimension('D')->setWidth(13);
        $workSheet->getColumnDimension('E')->setWidth(6);
        $workSheet->getColumnDimension('F')->setWidth(10);
        $workSheet->getColumnDimension('G')->setWidth(10);
//        $workSheet->getColumnDimension('C')->setWidth(5);
//        $workSheet->getColumnDimension('D')->setWidth(5);
//        $workSheet->getColumnDimension('E')->setWidth(5);
//        $workSheet->getColumnDimension('F')->setWidth(5);
        
        $workSheet->setTitle($valuePrint["tmnName"]);
        
        $workSheet->mergeCells('A1:B4')->getStyle(
            'A1:B4'
        )->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
        
        //set cell A1 content with some text
        $workSheet->setCellValue('D1', $valuePrint["companyName"]);
        $workSheet->getStyle('D1')->getFont()->setBold(true);
        
        $workSheet->setCellValue('K1', "Playback Report by Player");
        $workSheet->getStyle('K1')->getFont()->setBold(true);

        $workSheet->setCellValue('D3', "Player");
        $workSheet->getStyle('D3')->getFont()->setBold(true);
        $workSheet->setCellValue('E3', $valuePrint["tmnName"]);
        
        $workSheet->setCellValue('D4', "Player Group");
        $workSheet->getStyle('D4')->getFont()->setBold(true);
        $workSheet->setCellValue('E4', $valuePrint["groupName"]);
        
        
        $workSheet->setCellValue('D5', "Period");
        $workSheet->getStyle('D5')->getFont()->setBold(true);
        
        $workSheet->setCellValue('E5', "from");
        $workSheet->getStyle('E5')->getFont()->setBold(true);
        $workSheet->setCellValue('F5', $valuePrint["fromDate"]);
        $workSheet->setCellValue('H5', "to");
        $workSheet->getStyle('H5')->getFont()->setBold(true);
        $workSheet->setCellValue('I5',  $valuePrint["toDate"]);
        
        $workSheet->mergeCells('A7:M7')->getStyle(
            'A7:M7'
        )->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
        $workSheet->setCellValue('A8', "Summary ");
        $workSheet->getStyle('A8')->getFont()->setBold(true);
        $workSheet->setCellValue('A9', "Total Player ");
        $workSheet->setCellValue('A10', "Total Duration ");
        $workSheet->setCellValue('C9', count($this->countPlayer));
        $workSheet->setCellValue('C10', $this->sumDurationGroup[$key]);
        
        $workSheet->mergeCells('A12:M12')->getStyle(
            'A12:M12'
        )->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    }
    
    private function tableMedia($workSheet, $valuePrint){
        //header 
        $workSheet->mergeCells('I12:K12');
               $workSheet->setCellValue('I12', "Reference");
        
        $workSheet->setCellValue('A13', "ID");
        $workSheet->setCellValue('B13', "Player Group");
        $workSheet->mergeCells('C13:E13');
               $workSheet->setCellValue('C13', "Player");
               
        $workSheet->setCellValue('F13', "Start Time");
        $workSheet->setCellValue('G13', "Stop Time");
        $workSheet->setCellValue('H13', "Duration");
        $workSheet->setCellValue('I13', "PlayList");
        $workSheet->setCellValue('J13', "Zone");
        $workSheet->setCellValue('K13', "Story");
        
        //---//
        $workSheet->getStyle('I12:K12')->getFont()->setBold(true);
        $workSheet->getStyle('I12:K12')->getBorders()->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $workSheet->getStyle('I12:K12')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'EEEEEE')
                        )
                    )
                );
        $workSheet->getStyle('I12:K12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //---//
        //set ตัวหนา
        $workSheet->getStyle('A13:K13')->getFont()->setBold(true);
        //set เส้นขอบ
        $workSheet->getStyle('A13:K13')->getBorders()->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //ใส่สี จัดกลาง
        $workSheet->getStyle('A13:K13')->applyFromArray(
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
        $workSheet->getStyle('A13:K13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $row = 14;
        $id = 1;
        foreach ($valuePrint["data"] as $data) {
            $workSheet->setCellValue('A'.$row, $id);
            $workSheet->setCellValue('B'.$row, $data->tmn_grp_name);
            $workSheet->mergeCells('C'.$row.':E'.$row);
                   $workSheet->setCellValue('C'.$row, $data->tmn_name);

            $workSheet->setCellValue('F'.$row, $data->start_time);
            $workSheet->setCellValue('G'.$row, $data->stop_time);
            $workSheet->setCellValue('H'.$row, $data->duration);
            $workSheet->setCellValue('I'.$row, $data->pl_name);
            $workSheet->setCellValue('J'.$row, $data->dsp_name);
            $workSheet->setCellValue('K'.$row, $data->story_name);
            //set เส้นขอบ
            $workSheet->getStyle("A$row:K$row")->getBorders()->getAllBorders()
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
            $workSheet->getStyle("F$row:H$row")->applyFromArray(
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
        $workSheet->mergeCells('J13:L13');
               $workSheet->setCellValue('J13', "Reference");
        
        $workSheet->setCellValue('A14', "ID");
        
        $workSheet->mergeCells("B14:F14");
        $workSheet->setCellValue('B14', "Media");
//        $workSheet->mergeCells('C14:D14');
//               $workSheet->setCellValue('C14', "Start Time");
//               
////        $workSheet->setCellValue('F14', "Start Time");
//        $workSheet->mergeCells('E14:F14');
//               $workSheet->setCellValue('E14', "Stop Time");
        
        $workSheet->setCellValue('G14', "Start Time");
        $workSheet->setCellValue('H14', "Stop Time");
        $workSheet->setCellValue('I14', "Duration");
        $workSheet->setCellValue('J14', "PlayList");
        $workSheet->setCellValue('K14', "Zone");
        $workSheet->setCellValue('L14', "Story");
        
        //---//
        $workSheet->getStyle('J13:L13')->getFont()->setBold(true);
        $workSheet->getStyle('J13:L13')->getBorders()->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $workSheet->getStyle('J13:L13')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'EEEEEE')
                        )
                    )
                );
        $workSheet->getStyle('J13:L13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//        //---//
        //set ตัวหนา
        $workSheet->getStyle('A14:L14')->getFont()->setBold(true);
        //set เส้นขอบ
        $workSheet->getStyle('A14:L14')->getBorders()->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //ใส่สี จัดกลาง
        $workSheet->getStyle('A14:L14')->applyFromArray(
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
        $workSheet->getStyle('A14:L14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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

            $workSheet->setCellValue("G$row", $data->start_time);
            $workSheet->setCellValue("H$row", $data->stop_time);
            $workSheet->setCellValue("I$row", $data->duration);
            $workSheet->setCellValue("J$row", $data->pl_name);
            $workSheet->setCellValue("K$row", $data->dsp_name);
            $workSheet->setCellValue("L$row", $data->story_name);
        
        
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
            $workSheet->getStyle("G$row:I$row")->applyFromArray(
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
        $this->genReport(array('Attachment'=>1));
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
            ->display_as('tmn_desc', 'Desc')
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
    
    private function getValueFromObj($obj, $attr){
        if($obj != null){
            return $obj->$attr;
        }
        return NULL;
    }
    
    private function getValueFromArray($obj, $attr){
        if($obj != null){
            if(array_key_exists($attr, $obj)){
                return $obj[$attr];
            }
            return NULL;
        }
        return NULL;
    }
    
}