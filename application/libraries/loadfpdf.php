<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class loadfpdf {
    
    function loadfpdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'FPDF class is loaded.');
    }
 
    function load($param=NULL)
    {
        define('FPDF_FONTPATH', 'font/');
        $fpdf_path = APPPATH.'third_party/fpdf/fpdf.php';
        if(file_exists($fpdf_path)){
            require($fpdf_path);

            $path = APPPATH.'third_party/fpdf/pdf_html.php';

            if(file_exists($path)){
                include_once $path;
            }
        }
        
        
         
//        if ($params == NULL)
//        {
//            $param = '"en-GB-x","A4","","",10,10,10,10,6,3';          
//        }
         
        return new PDF_HTML();
    }
    

}