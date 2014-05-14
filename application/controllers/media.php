<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Media extends SpotOn {
    
    protected $media = null;
    function __construct() {
        parent::__construct();
        $this->load->library('getid3/getid3');
        $this->config->load('media', true);
        $this->media = $this->config->item('media');
        $this->indexArray = array("media_filename_temp", "text_input", "media_type_temp");
        
    }
    
    private function getCatId() {
//        $index = 'cat_id';
        $mediaId = $this->uri->segment(4);
        if($this->nullToZero($mediaId) != "0"){
            $media = $this->m->getMediaById($mediaId);
            foreach ($media as $key => $value) {
                return $this->getValueFromObj($value, "cat_ID");
            }
        }
        return NULL;
    }
            
    function index() {
        
        $cat_id = $this->getCatId();
        if(StringUtils::isStringNotBlankOrNull($cat_id)){
            $this->crud->where('mst_media.cat_ID', $cat_id);
            $this->session->set_userdata("cat_id",$cat_id);
        }
        
        
//        $mediaGroupList = $this->m->selectCat();
//        $this->mediaGroup = array();
//        foreach ($mediaGroupList as $mediaGroup) {
//            $this->mediaGroup[$mediaGroup->cat_ID] = $mediaGroup->cat_name;
//        }
        
        
        $cat = $this->m->selectCat("*");
        $catigory = array();
        foreach ($cat as $key => $value) {
            $catigory[$value->cat_ID] = $value->cat_name;
        }
        $this->crud->set_table('mst_media')
            ->where("mst_media.cpn_ID" , $this->cpnId)
            ->order_by("media_name", "DESC")
            ->set_relation("cat_id", "mst_media_cat", "cat_name")
            ->set_subject('Media')
            ->columns('cat_id', 'media_name', 'media_type', 'media_filename', 'media_size', 'media_lenght')
                 
            ->display_as('cat_id', 'Group')
            ->display_as('media_name', 'Name')
            ->display_as('media_type', 'Type')
            ->display_as('media_path', 'Path File')
            ->display_as('media_filename', 'File Name')
            ->display_as('media_filename_temp', 'File Name (.txt)')
            ->display_as('media_size', 'Size (byte)')
            ->display_as('media_lenght', 'Lenght (sec)')

        ->fields(
                'media_type',
                'media_filename', 
                'media_name', 
                'media_desc', 
                'cat_id', 
                'media_size', 
                'media_lenght', 
                'media_expire', 
                
                'media_path', 
                "page", 
                'media_checksum', 
                'text_input', 
                'media_filename_temp',
                'cpn_ID',
                "create_date", "create_by", "update_date", "update_by"
                )
                
               
//        ->field_type("media_filename_temp", "readonly")
//        ->field_type("cpn_ID", "hidden", $this->cpnId)
        ->field_type("media_path", "hidden")
        ->field_type("media_checksum", "hidden")
        ->field_type("media_lenght", "integer")     
//        ->field_type('cat_ID', 'dropdown', $this->mediaGroup, $this->nullToZero($cat_id))
        ->field_type("cat_id", "dropdown", $catigory, $cat_id)
        ->field_type('text_input', 'text')      
        ->required_fields('media_name', "media_filename", "media_expire", "media_lenght", 'media_type', 'cat_id')

        ->set_field_upload('media_filename', 'assets/uploads/media')
        ->callback_before_upload(array($this, 'callbackBeforeUpload'))
        ->callback_after_upload(array($this, 'callbackAfterUpload'))
                   
        ->callback_field('media_filename_temp',array($this,'_media_filename_temp'))
        ->callback_field('text_input',array($this,'_text_input'))
        ->callback_field('media_size',array($this,'_media_size'))
        ->callback_column('media_lenght',array($this,'_media_lenght'))
        ->callback_column('media_filename',array($this,'_media_filename'))        
                
        
//        ->callback_before_insert(array($this, 'callbackBeforeInsert'))     
//        ->callback_before_update(array($this, 'callbackBeforeUpdate'))
                ;
        $state = $this->crud->getState();
        $this->crud->setCustomScript(" "
                . "$(function(){ "
                . " if('$state' != 'add'){"
                . "     /* ถ้าเป็น video ห้ามแก้ */"
                . "     var query = '#field-media_type';"
                . "     if($('#field-media_type').val() == 'video'){"
                . "         query += ', #field-media_size, #field-media_lenght'"
                . "     }"
                . "     $(query).prop('readonly', true);"
                . "     $('#field-media_type').change().prop('disabled', true).trigger('liszt:updated');"
                . " }else{"
                . "     if('$cat_id' != ''){"
                . "         $('#field-cat_id').val($cat_id);"
                . "         $('#field_cat_id_chzn').find('span').html($('#field-cat_id :selected').html()).change();"
                . "     }"
                . " }"
                . "});");
        $this->output();
    }

    function _media_lenght($value = "", $field_info = "" , $file = null, $row = null){
        return $value / 1000;
    }
    
    function _media_filename($value = "", $field_info = "" , $file = null, $row = null){
        return "<a href='$field_info->media_path'>$value</a>";
    }
    
    function _media_filename_temp($value = "", $field_info = "" , $file = null, $row = null){
        $readonly = "";
        $state = $this->crud->getState();
        
        if($state === "edit"){
            $value = $row->media_filename;
            $readonly = "readonly='readonly'";
        }
        return "<input id='field-media_filename_temp' name='media_filename_temp' type='text' value='$value' $readonly/>";
    }
    
    
    function _text_input($value = "", $field_info = "" , $file = null, $row = null){
        $state = $this->crud->getState();
        
        $data = "";
        $textcolor = "";
        $textSize = "";
        $bgcolor = "";
        $playSpeed = "";
        $directionIn = "";
        
        if($state === "edit"){
            $media_path = $this->getMediaPath($row->media_filename);
            if(file_exists($media_path) && $row->media_filename != null && $row->media_filename!= ""){

                $sXML = file_get_contents($media_path, FILE_READ_MODE);
                
                $oXML = new SimpleXMLElement($sXML);

                
                $array = (array)$oXML;
                
                $data = $array["text"];
                $textcolor = $array["textcolor"];
                $textSize = $array["size"];
                $bgcolor = $array["backgroundcolor"];
                $playSpeed = $array["speed"];
                $directionIn = $array["direction"];
                
            }
        }
        
        
        $ret = '';

        $ret .= 'Text Color : <input type="text" id="textPicker" name="textcolor" value="'.$textcolor.'"></input> ';
            
        $ret .= ' <span style="margin-left:10px;" > ';
            $ret .= ' Text Size : <select id="textSize" name="textSize" >';

            $defaultTextSize = $this->media["defaultTextSize"];
                    
            foreach ($this->media["textSize"] as $size) {
                
                
                if($textSize == $size || ( $defaultTextSize == $size && $state == "add")){
                    $ret .= '<option value="' . $size . '" selected=selected >' . $size . '</option>';
                } else {
                    $ret .= '<option value="' . $size . '" >' . $size . '</option>';
                }
            }

            $ret .= '</select>';
        
        $ret .= ' </span> ';
        
//        defaultDirection
         $ret .= ' <span style="margin-left:10px;" > ';
            $ret .= ' Direction : <select id="direction" name="direction" >';

            $defaultDirection = $this->media["defaultDirection"];
                    
            foreach ($this->media["direction"] as $keyDirection => $direction) {
                
                
                if($directionIn == $keyDirection || ( $defaultDirection == $keyDirection && $state == "add")){
                    $ret .= '<option value="' . $keyDirection . '" selected=selected >' . $direction . '</option>';
                } else {
                    $ret .= '<option value="' . $keyDirection . '" >' . $direction . '</option>';
                }
            }

            $ret .= '</select>';
        
        $ret .= ' </span> ';
        
        
        $ret .= ' <div class="clear"></div> <br/>';
        
        $ret .= ' <span style="margin-left:10px;" > ';
        
            $ret .= 'Bg Color :  <input type="text" id="bgPicker" name="bgcolor" value="'.$bgcolor.'"></input> ';

            $ret .= ' <span style="margin-left:16px;"> ';
                $ret .= 'Speed : <select id="playSpeed" name="playSpeed" >';
                
                    $defaultPlaySpeed = $this->media["defaultPlaySpeed"];
                    
                    foreach ($this->media["playSpeed"] as $speed => $speedValue) {
                        if($playSpeed == $speedValue || ($defaultPlaySpeed == $speedValue && $state == "add") ){
                            $ret .= '<option value="' . $speedValue . '" selected=selected >' . $speed . '</option>';
                        } else {
                            $ret .= '<option value="' . $speedValue . '" >' . $speed . '</option>';
                        }
                        
                    }

                $ret .= '</select>';
            $ret .= '</span>';
        $ret .= '</span>';
        
        $ret .= ' <div class="clear"></div> <br/>';
        
        $ret .= ' <textarea name="input_text" id="field-text_input">' . $data . '</textarea> ';
        
        
        
        return $ret ;
    }
    
    function _media_size($value = "", $field_info = "" , $file = null, $row = null){
        
        $output = "<div id='size' style='padding-top:5px'> $value </div>";
        $output .= "<input name='media_size' id='field-media_size' style='width: 102.1px; display: none; ' type='text' maxlength='450' value='$value'/>";
                
        return $output;
    }
    
    function clearBeforeInsertAndUpdate($files_to_insert = "", $field_info = "" , $file = null, $row = null) {
        
        $type = $files_to_insert["media_type"];
        
        if($type === "scrolling text"){
            
            $files_to_insert = $this->writeFile($files_to_insert);
            $files_to_insert["media_type"] = "scrolling text";
            
            unset($files_to_insert["input_text"]);
            unset($files_to_insert["textcolor"]);
            unset($files_to_insert["textSize"]);
            unset($files_to_insert["bgcolor"]);
            unset($files_to_insert["playSpeed"]);
            unset($files_to_insert["direction"]);
        
        }
        $files_to_insert["media_lenght"] = $files_to_insert["media_lenght"] * 1000;
        $files_to_insert = parent::clearBeforeInsertAndUpdate($files_to_insert);
        $files_to_insert = parent::setDefaultValue($files_to_insert);
        return $files_to_insert;
    }
    
    function writeFile($files_to_insert = "", $field_info = "" , $file = null, $row = null){
        
        $state = $this->crud->getState();
        
        if($state === "insert"){
            //เฉพาะ mode insert เอาชื่อ media_name มาเป็นชื่อไฟล์
            $files_to_insert["media_filename"] = $files_to_insert["media_name"];
            
            $not_allowed_file_name = $this->media['not_allowed_file_name'];
            //validate ชื่อไฟล์
            $file = str_replace($not_allowed_file_name , "_", $files_to_insert["media_filename"]);

            do {
                 //gen prefix
                $uniqid = uniqid();
                //ชื่อไฟล์ที่จะใช้จริง
                $file_name = $uniqid."-".$file.".xml";
                //path ไฟล์จริงใน server
                $media_path = $this->getMediaPath($file_name);
            } while (file_exists($media_path));
            
            $files_to_insert["media_filename"] = $file_name;
            $files_to_insert["media_path"] = trim($this->media['text_url'], "/")."/" .$file_name;
        }else if($state === "update"){
//            $files_to_insert["media_filename"] = $files_to_insert["media_filename"].".xml";
            $media_path = $this->getMediaPath($files_to_insert["media_filename"]);
            
        }
        
        //เตรียมข้อมูลที่จะเขียนลงไฟล์
//        $writeDate = $files_to_insert["input_text"];
        
        $writeDate = $this->getDataFromPost($files_to_insert);
        
        
        file_put_contents($media_path, $writeDate, LOCK_EX);

        
        
        $files_to_insert["media_size"] = filesize($media_path);
        $files_to_insert["media_checksum"] = md5_file($media_path);
        return $files_to_insert;
    }
    
    private function getDataFromPost($files_to_insert){
        $data = $files_to_insert["input_text"];
        $textcolor = $files_to_insert["textcolor"];
        $textSize = $files_to_insert["textSize"];
        $bgcolor = $files_to_insert["bgcolor"];
        $playSpeed = $files_to_insert["playSpeed"];
        $direction = $files_to_insert["direction"];
        
        $this->load->library('xml_writer');
        $this->xml_writer->setRootName('scrollingtext');
        $this->xml_writer->initiate();
        $this->xml_writer->addNode('text', $data);
        $this->xml_writer->addNode('speed', $playSpeed);
        $this->xml_writer->addNode('backgroundcolor', $bgcolor);
        $this->xml_writer->addNode('textcolor', $textcolor);
        $this->xml_writer->addNode('size', $textSize);
        $this->xml_writer->addNode('direction', $direction);
        return $this->xml_writer->getXml(FALSE);
    }
    
    function callbackAfterUpload($files_to_upload = "", $field_info = "" , $file = null, $row = null) {

        $getID3 = new getID3;
        $root = $this->media['media_path'];
        $fileObj = $files_to_upload[0];
        $fileName = $root . $fileObj->name;

        $this->fileInfo = $getID3->analyze($fileName);
//        $ext = pathinfo($files_to_upload[0]->name, PATHINFO_EXTENSION);
        $ext = $this->fileInfo["fileformat"];

        $map = $this->media["extention"]["map"];
        if(array_key_exists($ext, $map)){
            $ext = $map[$ext];
        }
        
        if(in_array(strtolower($ext), array_map('strtolower', $this->media["extention"]["video"]))){
            $files_to_upload[0]->type = "video";
            $lenght = $this->fileInfo['playtime_seconds'];
            $files_to_upload[0]->lenght = number_format($lenght,4);
        }else if(in_array(strtolower($ext), array_map('strtolower', $this->media["extention"]["image"]))){
            $files_to_upload[0]->type = "image";
        }
        
        $files_to_upload[0]->ext = $ext;
        $files_to_upload[0]->path = $this->media['media_url'].'/'.$fileObj->name;

        $files_to_upload[0]->checkSum =  md5_file($fileName);


        $files_to_upload[0]->name = $fileObj->name;//str_replace($search, "" , $fileObj->name);

        return $files_to_upload;
    }

    function callbackBeforeUpload($files_to_upload, $field_info) {
        foreach ($files_to_upload as $value) {
            $ext = pathinfo($value['name'], PATHINFO_EXTENSION);
        }

        if (in_array(strtolower($ext), array_map('strtolower', $this->media["allowed_formats"]))) {
            return true;
        } else {
            return 'Wrong file format';
        }
    }
    
    
    public function _beforeDelete($primary_key) {
        return $this->m->checkDeleteMedia($primary_key);
    }
    
}




