<?php
/**
 * PHP grocery CRUD
 *
 * LICENSE
 *
 * Grocery CRUD is released with dual licensing, using the GPL v3 (license-gpl3.txt) and the MIT license (license-mit.txt).
 * You don't have to do anything special to choose one license or the other and you don't have to notify anyone which license you are using.
 * Please see the corresponding license file for details of these licenses.
 * You are free to use, modify and distribute this software, but all copyright information must remain.
 *
 * @package    	grocery CRUD
 * @copyright  	Copyright (c) 2010 through 2012, John Skoumbourdis
 * @license    	https://github.com/scoumbourdis/grocery-crud/blob/master/license-grocery-crud.txt
 * @version    	1.2
 * @author     	John Skoumbourdis <scoumbourdisj@gmail.com>
 */

// ------------------------------------------------------------------------

/**
 * Grocery CRUD Model
 *
 *
 * @package    	grocery CRUD
 * @author     	John Skoumbourdis <scoumbourdisj@gmail.com>
 * @version    	1.2
 * @link		http://www.grocerycrud.com/documentation
 */

class spot_on_model extends CI_Model  {
    
        private  $query_str = ''; 
        private  $cpnId = 0; 
	function __construct() {
		parent::__construct();
	}
        
        public function alter($sql){
            $this->db->query($sql);
        }
        
        public function setCpnId($cpn_ID){
            $this->cpnId = $cpn_ID;
        }
        
        public function getCpnById( $cpn_ID ){
            return $this->db->select("*")->where("cpn_ID", $cpn_ID)->get("mst_cpn")->result();
        }
        
        public function getCpn(){
            return $this->db->select("*")->get("mst_cpn")->result();
        }
        
        public function getWhere($where = "", $value = ""){
            $ret = array();
            if(is_array($where)){
                $ret = $where;
            } else if($where != null && $where != "") {
                $ret[$where] = $value;
            }
            $ret["cpn_ID"] = $this->cpnId;
            return $ret;
        }
        
        function getUserTypeCodeByUserId($userId){
            
            $count = $this->db->select("mst_user_type.user_type_code")
                        ->join('mst_user_type', 'mst_user_type.user_type_ID = mst_user.user_type_ID', 'inner')
                        ->where("user_ID" , $userId)
                        ->get("mst_user")
                        ->result();
            foreach ($count as $value) {
                return $value->user_type_code;
            }
            return NULL;
//            return $this->db->get('mst_media')->result();
        }
        
        function getUserTypeCodeById($userTypeId){
            
            $count = $this->db->select("user_type_code")
                        ->where("user_type_ID" , $userTypeId)
                        ->get("mst_user_type")
                        ->result();
            foreach ($count as $value) {
                return $value->user_type_code;
            }
            return NULL;
//            return $this->db->get('mst_media')->result();
        }
        
        function checkLogin($user, $pass){
            $count = $this->db->select("mst_user.cpn_ID, user_ID, user_displayname, mst_user_type.user_type_code, mst_cpn.cpn_name")
                        ->join('mst_user_type', 'mst_user_type.user_type_ID = mst_user.user_type_ID', 'inner')
                        ->join('mst_cpn', 'mst_user.cpn_ID = mst_cpn.cpn_ID', 'inner')
                        ->where("user_username" , $user)
                        ->where("user_password" , $pass)
                        ->get("mst_user")
                        ->result();
            foreach ($count as $value) {
                return $value;
            }
            return NULL;
//            return $this->db->get('mst_media')->result();
        }
        
        function getPermission($userId){
            $permission = $this->db->select("page_code, page_name")
                        ->join('mst_permission', 'mst_permission.permission_ID = trn_permission.permission_ID', 'inner')
                        ->where("user_ID" , $userId)
//                        ->where("user_password" , $pass)
                        ->get("trn_permission")
                        ->result_array();
            $ret = array();
            foreach ($permission as $value) {
                $ret[$value["page_code"]] = $value["page_name"];
            }
            return $ret;
//            return $this->db->get('mst_media')->result();
        }
        
        private function setDefaltCreateRow($row){
            $row["create_date"] = date('Y-m-d H:i:s');
        }
        
        public function deleteStoryItem($storyId){
            return $this->db->delete("trn_dsp_has_pl", array("story_ID" => $storyId));
        }
        
        public function deleteStory($storyId){
            return $this->db->delete("mst_story", array("story_ID" => $storyId));
        }
        
        public function deleteLayout($layoutId){
            return $this->db->delete("mst_lyt", array("lyt_ID" => $layoutId));
        }
        
        public function deleteDisplay($displayId){
            return $this->db->delete("mst_dsp", array("dsp_ID" => $displayId));
        }
        
        public function deleteDisplayByLayoutId($layoutId){
            return $this->db->delete("mst_dsp", array("lyt_ID" => $layoutId));
        }
        
        public function insertLayout($layout){
            $this->db->trans_start();
            $this->db->insert("mst_lyt", $layout);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
            return $insert_id;
        }
        public function insertStory($story){
            
            $this->setDefaltCreateRow($story);
            $this->db->trans_start();
            $this->db->insert("mst_story", $story);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
            return $insert_id;
        }
        
        public function updateStoryById($story, $storyId){
            $where = array(
                "story_ID" => $storyId
            );
            return $this->db->update("mst_story", $story, $where);
        }
        
        public function insertStoryItem($storyId, $dspId, $plId, $volumn){
            $set = array(
                "story_ID" => $storyId,
                "dsp_ID" => $dspId,
                "pl_ID" => $plId,
                "volumn" => $volumn
            );
            return $this->db->insert("trn_dsp_has_pl", $set);
        }
        
        
        public function insertDisplay($data){
            $this->db->trans_start();
            $this->db->insert("mst_dsp", $data);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
            return $insert_id;
        }
        public function updateDisplay($data){
            if(is_object($data)){
                $data = get_object_vars($data);
            }
            
            return $this->db->where("dsp_ID", $data["dsp_ID"])->update("mst_dsp", $data);
        }
        
        public function updateScreen($data){
            if(is_object($data)){
                $data = get_object_vars($data);
            }
            
            return $this->db->where("tmn_ID", $data["tmn_ID"])->update("mst_tmn", $data);
        }
        
        public function getResolutionByLayoutId($layoutId){
            return $this->db->select("lyt_width, lyt_height")->where("lyt_ID", $layoutId)->get('mst_lyt')->result();
        }
        
        public function getResolutionByGroupPlayerId($groupPlayerId){
            return $this->db->select("tmn_grp_width, tmn_grp_height")->where("tmn_grp_ID", $groupPlayerId)->get('mst_tmn_grp')->result();
        }
                
//        public function selectMedia($where = array(), $select = "cat_ID, media_ID, media_lenght, media_type") {
//            return $this->db->select($select)->where($where)->get('mst_media')->result();
//	}
                        
        public function selectMedia($where = array(), $select = "cat_ID, media_ID, media_lenght, media_type") {
            return $this->db->select($select)->where($this->getWhere($where))->get('mst_media')->result();
	}
        
        public function selectCat($select = "cat_ID, cat_name") {
            return $this->db->select($select)->where($this->getWhere())->get('mst_media_cat')->result();
	}
        
        public function selectTrnPlHasMediaByPlId($plId) {
            return $this->db->select("*")->where("pl_ID", $plId)->get('trn_pl_has_media')->result();
	}
        
        public function getScheduling() {
            return $this->db->get('mst_shd')->result();
	}
        
        public function getMedia() {
            return $this->db->select("*")->get('mst_media')->result();
	}
//        public function getSchedulingById($shdId){
//            return $this->db->select('*')->where('shd_ID', $shdId)->get('mst_shd')->result();
//        }
        
        public function getDeploymentById($dmpId) {
            return $this->db->where("dpm_ID", $dmpId)->get('trn_dpm')->result();
	}
        
//        public function getGroup() {
//            return $this->db->get('mst_media')->result();
//	}
        
        public function getMediaById($mediaId) {
            return $this->db->select('*')->where('media_ID', $mediaId)->get('mst_media')->result();
	}
        
        public function getStoryById($storyId) {
            return $this->db->select('*')->where($this->getWhere('story_ID', $storyId))->get('mst_story')->result();
	}
        
        public function getStoryItemByStoryId($storyId) {
            return $this->db->select('*')->where('story_ID', $storyId)->get('trn_dsp_has_pl')->result();
	}
        
        public function getPlaylistById($playlistId) {
            return $this->db->select('*')->where('pl_ID', $playlistId)->get('mst_pl')->result();
	}
        
        public function getDisplayById($displayId) {
            return $this->db->select('*')->where('dsp_ID', $displayId)->get('mst_dsp')->result();
	}
        
        public function getDisPlay() {
            return $this->db->select('dsp_ID, dsp_name, dsp_left, dsp_top, dsp_width, dsp_height, dsp_zindex, lyt_ID')->get('mst_dsp')->result();
	}
        
        public function getStory() {
            return $this->db->select('*')->where($this->getWhere())->get('mst_story')->result();
	}
        
        public function getLayout() {
            return $this->db->select('*')->where($this->getWhere())->get('mst_lyt')->result();
	}
        
        public function getTerminalGroup(){
            return $this->db->select('*')->where($this->getWhere())->get('mst_tmn_grp')->result();
        }
        
        public function getPlaylist() {
            return $this->db->select('*')->where($this->getWhere())->get('mst_pl')->result();
	}
        
        public function getTerminal(){
            return $this->db->select('*')->where($this->getWhere())->get('mst_tmn')->result();
        }
        
        public function getTerminalGroupByShdId($shdId){
            return $this->db->select('tmn_grp_ID')->where("shd_ID", $shdId)->get('trn_dpm')->result();
        }
        
        public function getTerminalById($tmnId){
            return $this->db->select('*')->where($this->getWhere("tmn_ID", $tmnId))->get('mst_tmn')->result();
        }
        
        public function getLayoutById($layoutId) {
            return $this->db->select('*')->where($this->getWhere('lyt_ID', $layoutId))->get('mst_lyt')->result();
	}
        
        public function getSchedulingById($shdId){
            return $this->db->select('*')->where('shd_ID', $shdId)->get('mst_shd')->result();
        }
        
        public function countDeploymentByShdId($shdId){
            
            $result = $this->db->select('count(*) as value')->where('shd_ID', $shdId)->get('trn_dpm')->result();
            foreach ($result as $value) {
                return $value->value;
            }
        }
        
        public function getDisplayByLayoutId($layoutId) {
            return $this->db->select('*')->where('lyt_ID', $layoutId)->where('lyt_ID != ', 0)->get('mst_dsp')->result();
	}
        
        public function getStoryByName($storyName) {
            return $this->db->select('*')->where($this->getWhere('story_name', $storyName))->get('mst_story')->result();
	}
        
        public function updateStory($story){
            $story = get_object_vars($story);
            return $this->db->where("story_ID", $story["story_ID"])->update("mst_story", $story);
        }
        
        public function updateLayout($layout) {
            $layout = get_object_vars($layout);
            return $this->db->where("lyt_ID", $layout["lyt_ID"])->update("mst_lyt", $layout);
	}
        
        public function insertOrUpdateStoryItemByStoryId($storyId, $dspId, $playlistId) {
            $result = $this->db->select('count(*) as value')->where('story_ID', $storyId)->get('trn_dsp_has_pl')->result();
            //ถ�?ามี�?ล�?ว�?ห�? update
            if($result[0]->value > 0){
                $data = array(
                    'dsp_ID' => $dspId,
                    'pl_ID' => $playlistId
                );
                $this->db->update("trn_dsp_has_pl", $data, array('story_ID' => $storyId));
            }else{
                 $data = array(
                    'story_ID' => $storyId,
                    'dsp_ID' => $dspId,
                    'pl_ID' => $playlistId
                );
                $this->db->insert("trn_dsp_has_pl", $data);
            }
	}
        
        public function getDisplayDropDownForStoryItem($storyId, $layoutId) {
            $ret = array();
            if($storyId == false || $layoutId == false){
                return $ret;
            }
            $sql = "SELECT display.* "
                . " FROM mst_story story "
                . " LEFT JOIN mst_lyt layout ON(story.lyt_ID = layout.lyt_ID)"
                . " LEFT JOIN mst_dsp display ON(layout.lyt_ID = display.lyt_ID)"
                . " WHERE story.story_ID = " . $storyId . " AND layout.lyt_ID = " . $layoutId;
            
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0)
            {
               foreach ($query->result() as $row)
               {
                    $ret[$row->dsp_ID] = $row->dsp_name;
               }
            }
            
            return $ret;
	}
        
        public function getDefaltDropDownValueForStoryItemByStoryId($storyId) {
            $defalt = new stdClass();
            $defalt->dsp_ID = NULL;
            $defalt->pl_ID = NULL;
            if($storyId == false || $storyId == NULL){
                return $defalt;
            }
            $sql = "SELECT display.dsp_ID, playlist.pl_ID"
                . " FROM trn_dsp_has_pl storyItem "
                . " LEFT JOIN mst_dsp display ON(storyItem.dsp_ID = display.dsp_ID)"
                . " LEFT JOIN mst_pl playlist ON(storyItem.pl_ID = playlist.pl_ID)"
                . " WHERE storyItem.story_ID = " . $storyId;
            
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0)
            {
               foreach ($query->result() as $row)
               {
                    return $row;
               }
            }
            return $defalt;
	}

        public function getPlaylistDropDownForStoryItemByCpnId($cpnId) {
            $sql = "SELECT * FROM mst_pl WHERE cpn_ID = " . $cpnId;
            $ret = array();
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0)
            {
               foreach ($query->result() as $row)
               {
                    $ret[$row->pl_ID] = array("name" => $row->pl_name,
                                              "desc" => $row->pl_desc,
                                              "duration" => $row->pl_usage);
               }
            }
            return $ret;
	}
        
        public function insertPlaylist($playlist) {
            $playlist["cpn_ID"] = $this->cpnId;
            $this->db->insert("mst_pl", $playlist);
            return $this->db->insert_id();
	}
        
        public function insertPlaylistItem($playlistId, $playlistItem) {
            $count = 1;
            foreach ($playlistItem as $value) {
                //เ�?�?�?�?าร Insert �?หม�?ทั�?�?หมด เ�?ราะ�?ด�?ทำ�?ารล�?�?�?หมด�?ล�?ว
                $pos = strpos($value, "/");
                if($pos !== false){
                    $value = substr($value, 0, $pos);
                }
                $data = array(
                    'media_ID' => $value,
                    'seq_no' => $count++,
                    'pl_ID' => $playlistId
                );
                 $this->db->insert("trn_pl_has_media", $data);
            }
	}
        
        public function insertDeployment($deployment) {
            $data = array(
                    "dpm_date" => date("YmdHis", time()),
                    "shd_ID" =>$deployment->shd_ID,
                    "tmn_grp_ID" =>$deployment->tmn_grp_ID,
                    "cpn_ID" => $this->cpnId
                );
            $this->db->insert("trn_dpm", $data);
            return $this->db->insert_id();
	}
        
        public function updateDeploymentByDpmId($deployment) {
            $dpmId = $deployment->dpm_ID;
            $data = array(
                    "dpm_date" => date("YmdHis", time()),
                    "shd_ID" =>$deployment->shd_ID,
                    "tmn_grp_ID" =>$deployment->tmn_grp_ID
                );
            $this->db->update("trn_dpm", $data, array("dpm_ID" => $dpmId));
            return $dpmId;
	}
        
        public function updateDeploymentByShdIdAndTmnGrp($shdId, $tmnGrp) {
            //ลบโดย $shdId
            $this->deleteDeploymentByShdId($shdId);
            $dpmDate = date("YmdHis", time());
            $data = array(
                    "dpm_date" => $dpmDate,
                    "shd_ID" => $shdId,
                    "cpn_ID" => $this->cpnId
                );
            foreach ($tmnGrp as $tmnGrpId) {
                $data["tmn_grp_ID"] = $tmnGrpId;
                
                $this->db->insert("trn_dpm", $data);
            }
	}
        
        
        public function updateDeploymentByShdIdAndTmnGrpId($tmnGrpId, $shdArr) {
            
            $this->deleteDeploymentByTmnGrpId($tmnGrpId);
            $resultDmpId = $this->getMaxDeploymentIdByShdIdAndTmnGrpId();
            $dmpId = ($resultDmpId[0]->VALUE == NULL ? 1 : $dmpId);
            $dpmDate = date("YmdHis", time());
            $data = array(
                    "dpm_date" => $dpmDate,
                    "tmn_grp_ID" =>$tmnGrpId
                );
            foreach ($shdArr as $shdId) {
                $data["shd_ID"] = $shdId;
                $this->db->insert("trn_dpm", $data);
            }
	}
        
        public function deleteDeploymentByShdId ($shdId){
            $data = array(
                'shd_ID' => $shdId
            );
            $this->db->delete("trn_dpm", $data);
        }
        
        public function deleteDeploymentByTmnGrpId ($tmnGrpId){
            $data = array(
                'tmn_grp_ID' => $tmnGrpId
            );
            $this->db->delete("trn_dpm", $data);
        }
        
        public function deleteTrnDspHasPlByStoryId ($storyId){
            $data = array(
                'story_ID' => $storyId
            );
            $this->db->delete("trn_dsp_has_pl", $data);
        }
        
        
        public function getMaxDeploymentIdByShdIdAndTmnGrpId() {
            return $this->db->select("(MAX(`dpm_ID`) + 1) AS VALUE")->where("dpm_ID >", "2")->get('trn_dpm')->result();
	}
        
        
        
        
        public function getMinStartDateShdByDpmId($dpmId) {
            return $this->db->select("min(`shd_start_date`) AS VALUE")->join("mst_shd", "mst_shd.shd_ID = trn_dpm.shd_ID")->where("dpm_ID", $dpmId)->get('trn_dpm')->result();
	}
        
        public function getMinStartTimeShdByDpmId($dpmId) {
            return $this->db->select("min(`shd_start_time`) AS VALUE")->join("mst_shd", "mst_shd.shd_ID = trn_dpm.shd_ID")->where("dpm_ID", $dpmId)->get('trn_dpm')->result();
	}
        
        public function getShdDescByDpmId($dpmId) {
            return $this->db->select("min(`shd_desc`) AS VALUE")->join("mst_shd", "mst_shd.shd_ID = trn_dpm.shd_ID")->where("dpm_ID", $dpmId)->get('trn_dpm')->result();
	}
        
        public function getTerminalStatusInIds($ids) {
            return $this->db->select("*")->where_in("tmn_ID", $ids)->get('mst_tmn')->result();
	}
        
        /* Check Delete */
        
        public function checkDeleteMedia($primary_key){
            //ตรวจสอบที่ playlist ว่ามีการเรียกใช้รึป่าว
            $result = $this->db->select("count(`media_ID`) AS VALUE")->where("media_ID", $primary_key)->get('trn_pl_has_media')->result();
	
            foreach ($result as $value) {
                return ($value->VALUE == 0);
            }
        }
        
        public function checkDeleteGroupMedia($primary_key){
            //ตรวจสอบที่ media ว่ามีการเรียกใช้รึป่าว
            $result = $this->db->select("count(`cat_ID`) AS VALUE")->where("cat_ID", $primary_key)->get('mst_media')->result();
	
            foreach ($result as $value) {
                return ($value->VALUE == 0);
            }
        }
        
        public function checkDeleteCompany($primary_key){
            //ตรวจสอบที่ media ว่ามีการเรียกใช้รึป่าว
            $result = $this->db->select("count(`cpn_ID`) AS VALUE")->where("cpn_ID", $primary_key)->get('mst_user')->result();
	
            foreach ($result as $value) {
                return ($value->VALUE == 0);
            }
            
        }
        
        public function checkDeletePlaylist($primary_key){
            //ตรวจสอบที่ display ว่ามีการเรียกใช้รึป่าว
            $result = $this->db->select("count(`pl_ID`) AS VALUE")->where("pl_ID", $primary_key)->get('trn_dsp_has_pl')->result();
	
            foreach ($result as $value) {
                return ($value->VALUE == 0);
            }
        }
        
        public function checkDeleteLayout($primary_key){
            //ตรวจสอบที่ story ว่ามีการเรียกใช้รึป่าว
            $result = $this->db->select("count(`lyt_ID`) AS VALUE")->where("lyt_ID", $primary_key)->get('mst_story')->result();
	
            foreach ($result as $value) {
                return ($value->VALUE == 0);
            }
        }
        
        
        public function checkDeleteDisplay($primary_key){
            //ตรวจสอบที่ ว่ามีการเรียกใช้รึป่าว
//            $result = $this->db->select("count(`dsp_ID`) AS VALUE")->where("dsp_ID", $primary_key)->get('trn_dsp_has_pl')->result();
//	
//            foreach ($result as $value) {
//                return ($value->VALUE == 0);
//            }
            //display ลบได้เลยไม่ต้องเช็ค
            return true;
        }
        
        public function checkDeleteStory($primary_key){
            
//            $ret = false;
            //ตรวจสอบที่ scheduling ว่ามีการเรียกใช้รึป่าว
            $result = $this->db->select("count(`story_ID`) AS VALUE")->where("story_ID", $primary_key)->get('mst_shd')->result();
            
            foreach ($result as $value) {
                return ($value->VALUE == 0);
            }
            
            //ถ้ามี scheduling เรียกใช้แล้ว ไม่ต้องเช็ค
//            if(!$ret){
//                //ถ้ามี story ผูกอยู่กับ display & playlist ไม่ให้ลบ
//                $result2 = $this->db->select("count(`story_ID`) AS VALUE")->where("story_ID", $primary_key)->get('trn_dsp_has_pl')->result();
//            
//                foreach ($result2 as $value) {
//                    return ($value->VALUE == 0);
//                }
//            }else{
//                return $ret;
//            }
            
        }
        
        public function checkDeleteScheduling($primary_key){
            //ตรวจสอบที่ trn_dpm ว่ามีการเรียกใช้รึป่าว
            $result = $this->db->select("count(`shd_ID`) AS VALUE")->where("shd_ID", $primary_key)->get('trn_dpm')->result();
	
            foreach ($result as $value) {
                return ($value->VALUE == 0);
            }
        }
        
        public function checkDeleteDeplayment($primary_key){
            //ตรวจสอบที่ trn_dpm ว่ามีการเรียกใช้รึป่าว
//            $result = $this->db->select("count(`shd_ID`) AS VALUE")->where("shd_ID", $primary_key)->get('trn_dpm')->result();
//	
//            foreach ($result as $value) {
//                return ($value->VALUE == 0);
//            }
            
            return false;
        }
        
        public function checkDeleteTerminalGroup($primary_key){
            //ตรวจสอบที่ ว่ามีการเรียกใช้รึป่าว
//            $result = $this->db->select("count(`tmn_grp_ID`) AS VALUE")->where("tmn_grp_ID", $primary_key)->get('trn_dpm')->result();
            $result = $this->db->select("count(`tmn_grp_ID`) AS VALUE")->where("tmn_grp_ID", $primary_key)->get('mst_tmn')->result();
            
            foreach ($result as $value) {
                return ($value->VALUE == 0);
            }
        }
        
        public function checkDeleteTerminal($primary_key){
            //ตรวจสอบที่ ว่ามีการเรียกใช้รึป่าว
            $result = $this->db->select("count(`tmn_ID`) AS VALUE")->where("tmn_ID", $primary_key)->get('trn_dpm')->result();
            $result2 = $this->db->select("count(`tmn_grp_master_ID`) AS VALUE")->where("tmn_grp_master_ID", $primary_key)->get('mst_tmn_grp')->result();
            
            $ret = false;
            $ret2 = false;
            foreach ($result as $value) {
                $ret = ($value->VALUE == 0);
            }
            
            foreach ($result2 as $value) {
                $ret2 = ($value->VALUE == 0);
            }
            
            return $ret && $ret2;
        }
        
        public function getMediaTemp(){
            $result = $this->db->select("*")
                    ->where($this->getWhere("item_type", "media"))
                    ->order_by("item_name")
                    ->get('log_item')->result();
            return $this->getItem($result);
        }
        
        public function getPlayerTemp(){
            $result = $this->db->select("*")
                    ->where($this->getWhere("item_type", "tmn"))
                    ->order_by("item_name")
                    ->get('log_item')->result();
            return $this->getItem($result);
        }
        
        public function getPlayerGroupTemp(){
            $result = $this->db->select("*")
                    ->where($this->getWhere("item_type", "tmn_grp"))
                    ->order_by("item_name")
                    ->get('log_item')->result();
            return $this->getItem($result);
        }
        
        private function getItem($result){
//            $ret = array();
//            $count = count($result);
//            foreach ($result as $value) {
//                $ret[$value->item_ID] = $value->item_name;
//            }
            return $result;
        }
        
        public function getLogItemByItemId($itemId, $itemType, $select = "item_name"){
            $result = $this->db->select($select)
                    ->where($this->getWhere(array("item_ID" => $itemId, "item_type" => $itemType)))
                    ->get('log_item')->result();
            foreach ($result as $value) {
                return $value->item_name;
            }
            
        }
        
        public function insertLogItem($set){
            $this->db->insert("log_item", $set);
        }
        
        public function updateTerminal($terminal){
            $this->db->update("mst_tmn", (array)$terminal, array("tmn_ID" => $terminal->tmn_ID) );
        }
        
        public function getDataForReport($_get){
//            $genReportBy = $_get["genReportBy"];
            $playerGroup = $_get["playerGroup"];
            $player = $_get["player"];
            $media = $_get["media"];
            $fromDate = $_get["fromDate"];
            $toDate = $_get["toDate"];
            
            $where = array( );
            if($playerGroup != 0){
//                $where["tmn_grp_ID"] = $playerGroup;
                $where["tmn_grp_name"] = $this->getLogItemByItemId($playerGroup, "tmn_grp");
            }
            if($player != 0){
//                $where["tmn_ID"] = $player;
                $where["tmn_name"] = $this->getLogItemByItemId($player, "tmn");
            }
            if($media != 0){
//                $where["media_ID"] = $media;
                $where["media_name"] = $this->getLogItemByItemId($media, "media");
            }
            if($fromDate != null || $fromDate != ""){
                $startDateTime = $this->getStringDateFormat($fromDate);
                $where["start_date >="] = date("Y-m-d", strtotime($startDateTime)) ;
            }
            if($toDate != null || $toDate != ""){
                $stopDateTime = $this->getStringDateFormat($toDate);
                $where["start_date <="] = date("Y-m-d", strtotime($stopDateTime));
            }
             $this->db->select("*")
                ->where($this->getWhere($where)); 
            
            $genBy = $_get["genReportBy"];
            $orderBy = "";
            if($genBy == 1){
                $orderBy = "tmn_name";
            } else if($genBy == 2){
                //Media
                $orderBy = "media_name";
            }
            $orderBy .= ", start_date, start_time";
            $this->db->save_queries = FALSE;
            $this->db->from('log');
            $query = $this->db->order_by($orderBy)->get();
            
            $data = $query->result();

            return    $data;
        }

        public function insertLog($set){
            $this->db->insert("log", $set);
        }
        
        private function getStringDateFormat($date){
            $date = explode("/", $date); // dd mm yy
            $date = array_reverse($date);
            foreach ($date as $key => $value) {
                if(strlen($value) == 1){
                    $date[$key] = "0" . $value;
                }
            }
            return implode('-', $date);
        }
        
        
        public function getSelectUserReceiveAlert(){
            $result = $this->db->select("*")
                ->where($this->getWhere())
                ->where('user_receive_sms = true OR user_receive_email = true ')
//                ->or_where(array('user_receive_sms' => true, 'user_receive_email' => true))
                ->order_by('user_username')
                ->get('mst_user')->result_array();
            
            $ret = array();
            foreach ($result as $array) {
                $ret[$array['user_ID']] = $array['user_username'];
            }
            
            return $ret;
//            SELECT *, user_username as s254b66a7 FROM (`mst_user`) WHERE `mst_user`.`cpn_ID` = '1' ORDER BY `mst_user`.`user_username`
        }
}



