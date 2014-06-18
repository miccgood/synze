
 <?php 
 
    function getValueInObj($obj, $index){
        if(is_object($obj)){
            if(property_exists($obj, $index)){
                return $obj->$index;
            }
        }
        
        return "";
    }
//    getValueInObj(getValueInArr(),)
    function getValueInArr($arr, $index){
        if(is_array($arr)){
            if(array_key_exists($index, $arr)){
                return $arr[$index];
            }
        }
        
        return "";
    }
    
    $layout = array();
    
    foreach ($default_value["layout"] as $key => $value) {
        $layout["$key"] = $value;
    }
 
    
    $story = null;
    foreach ($default_value["story"] as $key => $value) {
        $story = $value;
    }
    
    
    $storyItem = array();
    $storyItemMapPl = array();
    $storyItemMapVolumn = array();
    foreach ($default_value["storyItem"] as $key => $value) {
        $storyItem[$value->dsp_ID] = $value;
        
        $storyItemMapPl[$value->dsp_ID] = $value->pl_ID;
        
        $storyItemMapVolumn[$value->dsp_ID] = $value->volumn;
    }
 
    
    $displayAll = array();
    foreach ($default_value["displayAll"] as $key => $value) {
        $displayAll[$value->dsp_ID] = $value;
    }
//    $display = array();
//    foreach ($default_value["display"] as $key => $value) {
//        $display[$value->dsp_ID] = $value;
//    }
    
 ?>





        
<style>

    .middle {
        position: absolute;
        display: table-cell; 
        text-align: center;
        width: 100%;
        /*background: gainsboro;*/
        border-radius: 1px 1px 1px 1px;
        height: 100%;
        vertical-align: middle;

        /*background: gainsboro;*/
        /*background: url('radial-gradient.png') 50% 50% no-repeat;*/
        /*background: radial-gradient(#efefef, ‪#cdcdcd‬);*/
        /* For Safari */
        background: -webkit-repeating-radial-gradient(#fff, #dedede);
        /* For Opera 11.1 to 12.0 */
        background: -o-repeating-radial-gradient(#fff, #dedede);
        /* For Firefox 3.6 to 15 */
        background: -moz-repeating-radial-gradient(#fff, #dedede);
        /* Standard syntax */
        background: repeating-radial-gradient(#fff, #dedede);

    }

    .layout-default {
        position: absolute;
        background-color: lavender;
        width: 80px;
        height: 80px;
        margin:1px;
    }
    #container { width: 640px; height: 360px; position: relative; z-index: 100;margin: auto;border: 2px solid gray;overflow:hidden;}
    #container h3 { text-align: center; margin: 0; margin-bottom: 10px; background-color: cornsilk; }
    #layout1 {z-index: 101;}
/*    .grayInput {background-color: #dedede;}*/
    div.inline { float: left;}
    
    /* #layout1 { background-position: top left; width: 150px; height: 150px; }*/
    /*#layout1, #container { padding: 0.0em; }*/
    
    

    .slider-tooltip {
        position: absolute;
        z-index: 1020;
        display: block;
        padding: 5px;
        font-size: 11px;
        visibility: visible;
        margin-top: -2px;
        bottom:-35%;
        margin-left: -0.6em;
    }

    .slider-tooltip .slider-tooltip-arrow {
        bottom: 0;
        left: 50%;
        margin-left: -5px;
        border-top: 5px solid #000000;
        border-right: 5px solid transparent;
        border-left: 5px solid transparent;
        position: absolute;
        width: 0;
        height: 0;
    }

    .slider-tooltip-inner {
        max-width: 200px;
        padding: 1px 6px;
        color: #000;
        text-align: center;
        text-decoration: none;
        background-color: #e7e7e7;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }


</style>
<?php $uniqid = uniqid(); ?>
<script>
    var $_layoutTemplate = null;
    var $_layoutCount = 1;
    var $_layoutZIndexCount = 101;
    var $_containerId = "#container";
    
   
    var $_layoutWidth = parseInt(<?php echo getValueInObj(getValueInArr($layout, "0"),"lyt_width");  ?>);//1280
    var $_layoutHeight = parseInt(<?php echo getValueInObj(getValueInArr($layout, "0"),"lyt_height"); ?>);//720
    var $_maxZIndex = -1;
    var $_tableId = "<?php echo $uniqid; ?>";
    
    
    var $_tableTemplate = null;
    var $_trTemplate = $("<tr></tr>");
    var $_tdTemplate = $('<td class="trigger-td-input"></td>');
    
    
    var $_resWidth = 0;//1280
    var $_resHeight = 0;//720
    
    var $_containerWidthTemplate = parseInt($($_containerId).width());;//640px
    var $_containerHeightTemplate = parseInt($($_containerId).height());//360px
    
    $(function() {
        
        $_containerWidthTemplate = parseInt($($_containerId).width());;//640px
        $_containerHeightTemplate = parseInt($($_containerId).height());//360px
    
        initContainer($_containerId);
        $_tableTemplate = $("#"+$_tableId).find("tbody");
        $_layoutTemplate = $("#layout1").clone(true);
        $("#layout1").hide();
//        bindEvent("#layout1");
//        $("button").button();

        $("#addLayout").click(function() {
            var $layoutCount = ++$_layoutCount;
            //รูปแบบ id
//            1 แบบโหลดมาจาก DB จะให้ ID จาก DB เลย
//            2 สร้างใหม่จากหน้า จะมีคำว่า gen- นำหน้า ตามด้วยเลย count
            var newLayoutId = "gen-" + $layoutCount;
            createLayout(newLayoutId, "Zone", 0, 0, 200, 200, null);
            
            var $trId = "row-" + newLayoutId;
            createRow($trId, "Zone", 0, 0, 200, 200, null);
        });


        <?php 
            $display = array();

            foreach ($default_value["display"] as $key => $value) {
                $display[$value->dsp_ID] = $value;
                
                echo "createLayout(" . nullToZero($value->dsp_ID)
                . ",'". nullToZero($value->dsp_name) . "',". 
                        nullToZero($value->dsp_top) . ",". 
                        nullToZero($value->dsp_left) . ",". 
                        nullToZero($value->dsp_width) . ",". 
                        nullToZero($value->dsp_height) . ",". 
                        nullToZero($value->dsp_zindex) .
                        ");";
            }
            
            
            function nullToZero($param , $ret = "0") {
                return ($param === FALSE || $param === NULL || $param === "" ? $ret : $param);
            }
        ?>
    
//        setCenter($("#layout1"));
    });

//    var initContainer = function($containerId){
//        
////        ดึงความกว้างจอการแสดงผล
//        var width = parseInt($($containerId).width());//640px;  360px;
//        var height = parseInt($($containerId).height());
//        //คำนวนความต่างของจอจริง กับค่าที่มาจาก DB เพิ่มนำไป render
//        $_resWidth = $_layoutWidth / width;
//        $_resHeight = $_layoutHeight / height;
//        
//    };

var initContainer = function($containerId){
        //set ค่ากลับเป็นแบบเริ่มต้นก่อน
        $($containerId).width($_containerWidthTemplate);
        $($containerId).height($_containerHeightTemplate);
        
        //ข้อมูลส่วนแสดงผลบนจอ
        var width = parseInt($($containerId).width());//640px;  360px;
        var height = parseInt($($containerId).height());
        
        //ข้อมูลที่เลือก
        $_layoutHeight = parseInt($_layoutHeight);
        $_layoutWidth = parseInt($_layoutWidth);
        
        if($_layoutHeight > $_layoutWidth ){
            $($containerId).width(height);
            $($containerId).height(width);
            
            width = parseInt($($containerId).width());//360px; 640px;  
            height = parseInt($($containerId).height());
        } else if($_layoutHeight == $_layoutWidth ) {
            $($containerId).width(height);
            $($containerId).height(height);

            width = parseInt($($containerId).width());//360px;  360px;
            height = parseInt($($containerId).height());
        }
//        ดึงความกว้างจอการแสดงผล
       
        //คำนวนความต่างของจอจริง กับค่าที่มาจาก DB เพิ่มนำไป render
        
        $_resWidth = $_layoutWidth / width;
        $_resHeight = $_layoutHeight / height;
        
    };
    
    
    var setOffset = function($obj, x, y, width, height) {
//        $obj.css({top: y, left: x, width: width, height: height});
        setPosition($obj, x, y);
        setSize($obj, width, height);
    };

    var setPosition = function($obj, y, x) {
        
        var displayX = parseInt(x / $_resWidth);
        var displayY = parseInt(y / $_resHeight);
        $obj.css({top: displayY, left: displayX});
    };

    var setSize = function($obj, width, height) {
        var displayWidth = parseInt(width / $_resWidth);
        var displayHeight = parseInt(height / $_resHeight);
        $obj.css({width: displayWidth, height: displayHeight});
    };
    
    var setZIndex = function($obj, $zindex) {
        $obj.zIndex($zindex);
    };

    var setCenter = function($newLayout) {
        var $parentWidth = parseInt($($_containerId).width(), 10);
        var $parentHeight = parseInt($($_containerId).height(), 10);
        var $width = parseInt($newLayout.width(), 10);
        var $height = parseInt($newLayout.height(), 10);
//วิธีคือ เอาโหนดแม่หารสอง ลบ โหนดลูกหารสอง
        var $widthResult = ($parentWidth / 2) - ($width / 2);
        var $heightResult = ($parentHeight / 2) - ($height / 2);
        setPosition($newLayout, $widthResult, $heightResult);
    };

    var createLayout = function(id, name, top, left, width, height, zindex) {
        var $newLayout = $_layoutTemplate.clone(true);
        $newLayout.attr("id", id)
                .css({zIndex: ++$_layoutZIndexCount}).show();

        $newLayout.find("span.middle").html(name).show();

        $($_containerId).prepend($newLayout);
//        bindEvent("#" + id);
        setPosition($newLayout, top, left);
        setSize($newLayout, width, height);
        $_maxZIndex = getMax($_maxZIndex, zindex);
        if(zindex == null || typeof zindex == "undefined"){
            zindex = $_maxZIndex++;
        }
        setZIndex($newLayout, zindex);
    };
    
    var getMax = function($maxZIndex, $zindex){
        return ($maxZIndex >= $zindex ? $maxZIndex : $zindex);
    }
    
    var createRow = function(id, name) {
        $_tableTemplate.find('.dataTables_empty').parent("tr").hide();
        //หา tr ที่ show อยู่
        var $count = $_tableTemplate.find("tr").find(':visible').size();
        var $tr = $_trTemplate.clone(true);
        
        var $tdName = $_tdTemplate.clone(true);
//        var $tdDesc = $_tdTemplate.clone(true);
        var $tdPlaylist = $_tdTemplate.clone(true);
        var $tdDuration = $_tdTemplate.clone(true);
        var $tdVolumn = $_tdTemplate.clone(true);
          
        var $selectTemplate = getPlaylistTemplate();
        var $volumnTemplate = getVolumnTemplate();
        
        $tr.attr("id", id)
        .append($tdName.attr("name", "dsp_name").html(name))
//        .append($tdDesc.attr("name", "pl_desc").html(""))
        .append($tdPlaylist.attr("name", "playlist").html($selectTemplate))
        .append($tdDuration.attr("name", "duration").html(getFormatTime(0)))
        .append($tdVolumn.attr("name", "volumn").html($volumnTemplate))
        .addClass(($count%2 === 0 ? "odd" : "even"))
        ;
        
        $_tableTemplate.append($tr);
        
//        bindEventRow();
//        bindEventDelete();
    };
    
    
    
    var refreshLayout = function(id, name, top, left, width, height, zindex) {
        var $zone = $($_containerId).find("#" + id);
        $zone.find("span.middle").html(name);
        bindEvent("#" + id);
        setPosition($zone, top, left);
        setSize($zone, width, height);
        setZIndex($zone, zindex);
    };

    var removeLayout = function(id) {
        $($_containerId).find("#" + id).remove();
    };
    
    var removeRow = function(id) {
        $_tableTemplate.find('.dataTables_empty').hide();
        
        $_tableTemplate.find("#"+id).remove();
        
        $_tableTemplate.find("tr").each(function(index, value){
            $(this)
                .removeClass("odd")
                .removeClass("even")
                .addClass((index%2 === 0 ? "odd" : "even"));
        });
    };
    
    var bindEvent = function(id) {

        $(id).resizable({
            containment: $_containerId
            , scroll: false
            , start: function(e, ui) {
                var $div = $(e.target);
                var $divId = $div.attr("id");
                printSize($divId, ui);
// alert('resizing started');
            },
            resize: function(e, ui) {
                var $div = $(e.target);
                var $divId = $div.attr("id");
                printSize($divId, ui);
// $( id ).css({top: "auto", left: "auto"});
            },
            stop: function(e, ui) {
                var $div = $(e.target);
                var $divId = $div.attr("id");
                printSize($divId, ui);
            }
        }).draggable({
            containment: $_containerId,
            cursor: "click",
            scroll: false,
            start: function(e, ui) {
                
                var $div = $(e.target);
                var $divId = $div.attr("id");
                printPosition($divId, ui);
// alert('resizing started');
            },
            drag: function(e, ui) {
                
                var $div = $(e.target);
                var $divId = $div.attr("id");
                printPosition($divId, ui);
// $( id ).css({top: "auto", left: "auto"});
            },
            stop: function(e, ui) {
                var $div = $(e.target);
                var $divId = $div.attr("id");
                printPosition($divId, ui);
//                var $div = $(e.target);
                printoffset($div);
            }
        }).click(function(e) {
            var $div = $(e.target).parent("div");
            printoffset($div);
        });
    };
    var printoffset = function($div) {
        var $divId = $div.attr("id");
        var $position = $div.position();
        $position.position = $position;
        printPosition($divId, $position);

        $position.size = $div.offset();
        $position.size.width = $div.width();
        $position.size.height = $div.height();
        printSize($divId, $position);
    };

    var printSize = function($divId, ui) {
        var width = ui.size.width;
        var height = ui.size.height;
        
        
        var displayWidth = parseInt(width * $_resWidth);
        var displayHeight = parseInt(height * $_resHeight);
        $("#row-"+ $divId).find("td[name=dsp_width]").html((displayWidth < 0 ? 0 : displayWidth));
        $("#row-"+ $divId).find("td[name=dsp_height]").html((displayHeight < 0 ? 0 : displayHeight));
//        $("#printWidth").html(width);
//        $("#printHeight").html(height);
    };

    var printPosition = function($divId, ui) {
        var y = 0;
        var x = 0;
        try {
            y = ui.position.top;
            x = ui.position.left;
        } catch (err) {
// y = ui.top;
// x = ui.left;
        } finally {
            
            var displayTop = parseInt(y * $_resHeight);
            var displayLeft = parseInt(x * $_resWidth);
        $("#row-"+ $divId).find("td[name=dsp_top]").html((displayTop < 0 ? 0 : displayTop));
        $("#row-"+ $divId).find("td[name=dsp_left]").html((displayLeft < 0 ? 0 : displayLeft));
//        $("#row-"+ $divId).find("td[name=dsp_top]").html(y);
//        $("#row-"+ $divId).find("td[name=dsp_left]").html(x);
//            $("#printX").html(y);
//            $("#printY").html(x);
        }

    };
    
    var clearScreen = function($containerId){
        $($containerId).children().not("#layout1").remove();
    };
    
    var clearRow = function(){
        $("#" + $_tableId).find("tbody").find("tr").remove();
        
        $("#" + $_tableId).find("tbody").append("<tr class='odd'> <td class='dataTables_empty' valign='top' colspan='4'> No items to display </td> </tr>").show();
    };


// $( "#layout1" ).css({top: "auto", left: "auto"});
</script>
<div class='clear' style="width: 100%; height: 20px; min-width: 1024px;"></div>
<div style="width: 100%;min-width: 1024px;">

    <div id="container" class="ui-widget-content">
        <div id="layout1" class="ui-state-active layout-default" style="display:none;">
            <span class="middle">
                Layout1
            </span>
        </div>
    </div>
</div>



<div class='clear' style="width: 100%; height: 20px;"></div>
<!--position: relative; margin: auto;-->
<div style="min-width: 1024px; width: 100%;" class="divDetail">
    <div style="width: 1220px; position: relative; z-index: 100;margin: auto;" class="divDetail">

        <div class="inline">
            <table cellpadding="50px" cellspacing="50px" border="1" class="display" style="min-width: 300px;border: 2px solid #E5EFFD;">
                <tr>
                    <td style="min-width: 70px;">Story Name <span class="required"> * </span></td> 
                    <td> 
                        <input type="hidden" value="<?php echo getValueInObj($story, "story_ID");?>" id="storyId"/>
                        <input type="text" value="<?php echo getValueInObj($story, "story_name");?>" id="storyName"/>
                    </td>
                </tr>
                <tr>
                    <td>Description </td> <td> <input type="text" value="<?php echo getValueInObj($story, "story_desc");?>" id="storyDesc"/></td>
                </tr>
                <tr>
                    <td>Layout <span class="required"> * </span></td> <td> 
                    <?php
                    
                        $select = "<select id='selectLayout' class='chosen-select' style='width:200px;' >";
                        $select .= "<option value='0'></option>";
                        foreach ($default_value["layoutAll"] as $key => $value) {
                            $layoutId = $value->lyt_ID;
                            $layoutAll[$layoutId] = $value;
                            $innerHtml = substr($value->lyt_name, 0, 20);
                            $selected = (getValueInObj(getValueInArr($layout, "0"), "lyt_ID") == $layoutId ? "selected='selected'" : "" );
                            $select .= "<option value='$value->lyt_ID' $selected> $innerHtml </option>";
                            
                        }
                            
                        $select .= "</selected>";
                    echo $select;
                    ?>
                    
                    
                    </td>
                </tr>
                
                <tr>
                    <td>Duration </td> <td> <input type="text" id="inputDuration" value="" readonly="readonly" class="grayInput" /></td>
                </tr>
                
                <?php 
                    $ci = &get_instance();
                    $mode = ( $ci->getStoryId() == "0" ? "add" : "edit" ) ;
                    $save = "";
                    $save_and_go_back = "";
                    if($mode == "add"){
                        $save = $this->l('form_save');
                        $save_and_go_back = $this->l('form_save_and_go_back');
                    } else if($mode == "edit"){
                        $save = $this->l('form_update_changes');
                        $save_and_go_back = $this->l('form_update_and_go_back');
                    }
                ?>
                <tr>
                    <td colspan="2" align="center"> 
                    <?php if($this->default_value["permissionEdit"]){ ?>
                        <span class="datatables-add-button">
                            <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" >
                                    <span class="ui-button-icon-primary ui-icon ui-icon-circle-check"></span>
                                    <span class="ui-button-text" id="save"> <?php echo $save; ?> </span>
                                    <!--<button id="addLayout"> Add Layout </button>-->
                            </a>
                        </span>

                        <span class="datatables-add-button">
                            <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" >
                                    <span class="ui-button-icon-primary ui-icon ui-icon-circle-check"></span>
                                    <span class="ui-button-text" id="save_and_go_back_to_list"> <?php echo $save_and_go_back; ?> </span>
                                    <!--<button id="addLayout"> Add Layout </button>-->
                            </a>
                        </span>


                        <?php if(!is_null($back_url) && $back_url != ""){?>

                            <span class="datatables-add-button">
                                <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary back-to-list" href="<?php echo $back_url?>" onclick="return confirmGoBackToList()">
                                        <span class="ui-button-icon-primary ui-icon ui-icon-circle-close"></span>
                                        <span class="ui-button-text">Cancel</span>
                                </a>
                            </span>
                        <?php }?>

                    <?php } else {  ?>
                        <?php if(!is_null($back_url) && $back_url != ""){?>

                            <span class="datatables-add-button">
                                <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary back-to-list" href="<?php echo $back_url?>" onclick="return confirmGoBackToList()">
                                        <span class="ui-button-icon-primary ui-icon ui-icon-arrow-1-w"></span>
                                        <span class="ui-button-text">Back</span>
                                </a>
                            </span>
                        <?php }?>
                    <?php }?>
        
        
        
        
                        <script type="text/javascript">
                            function confirmGoBackToList(){
                                var message_alert_edit_form = "The data you had change may not be saved.\nAre you sure you want to go back to list?";
                                if( $(this).hasClass('back-to-list') || confirm( message_alert_edit_form ) )
                                {
                                         return true;
                                }
                                return false;
                            }

                        </script>

                    </td>
                </tr>
                
                <tr>
                    <td colspan="2" align="center"> 
                        <div class='form-button-box loading-box' style="position: relative; margin: auto; width: 70%; float: none;">
				<div class='small-loading' id='FormLoading'><?php echo $this->l('form_insert_loading'); ?></div>
			</div>
			<div class='clear'></div>
                        
                    </td>
                </tr>
            </table>
        </div>
        <div style="min-width: 30px;max-width: 100px ;" class="inline"> &nbsp;</div>
        <div class="inline">
            <table cellpadding="0" cellspacing="0" border="0" class="display groceryCrudTable" id="<?php echo $uniqid; ?>" style="border: 2px solid #E2E4FF;">
                    <thead>
                            <tr>
                                    <?php foreach($columns as $column){?>

             <!---				<th><?php echo $column->display_as; ?></th>-->
                                <th style="text-align:center;" name="<?php echo $column->field_name ?>"><?php echo $column->display_as; ?> 
                                    <span id="tooltip-<?php echo $column->field_name ?>" style="margin-right: -10px;"></span>
                                </th>
                                    <?php }?>
                            </tr>
                    </thead>
                    <tbody>
                            <?php foreach($list as $num_row => $row){ ?>
                            <tr id='row-<?php echo $row->dsp_ID?>'>
                                    <?php foreach($columns as $column){?>
                                            <!--<td><?php echo $row->{$column->field_name}?></td>-->
                                            <?php $text_align = (empty($column->align) ? '' : ('align="'.$column->align.'"')); ?>
                                            <td <?php echo $text_align;?> name="<?php echo $column->field_name ?>"><?php echo $row->{$column->field_name}?></td>
                                    <?php }?>
                            </tr>
                            <?php }?>
                    </tbody>
                    <tfoot style="display:none;">
                            <tr>
                                    <?php foreach($columns as $column){?>
                                            <th><input type="text" name="<?php echo $column->field_name; ?>" placeholder="<?php echo $this->l('list_search').' '.$column->display_as; ?>" class="search_<?php echo $column->field_name; ?>" /></th>
                                    <?php }?>
                                    <?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
                                            <th>
                                                    <button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only floatR refresh-data" role="button" data-url="<?php echo $ajax_list_url; ?>">
                                                            <span class="ui-button-icon-primary ui-icon ui-icon-refresh"></span><span class="ui-button-text">&nbsp;</span>
                                                    </button>
                                                    <a href="javascript:void(0)" role="button" class="clear-filtering ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary floatR">
                                                            <span class="ui-button-icon-primary ui-icon ui-icon-arrowrefresh-1-e"></span>
                                                            <span class="ui-button-text"><?php echo $this->l('list_clear_filtering');?></span>
                                                    </a>
                                            </th>
                                    <?php }?>
                            </tr>
                    </tfoot>
            </table>

        </div>

    </div>
</div>
<div class='clear' style="width: 100%; height: 1px;"></div>
<script type="text/javascript">
    
    var $_tableId = "<?php echo $uniqid; ?>";
    var $_playlist = playlist;
    var $_display = <?php  echo json_encode($display); ?>;
    var $_storyItemMapPl = <?php  echo json_encode($storyItemMapPl); ?>;
    var $_storyItemMapVolumn = <?php  echo json_encode($storyItemMapVolumn); ?>;
    
    var $_displayAll = <?php  echo json_encode($displayAll); ?>;
    var $_layoutAll = <?php  echo json_encode($layoutAll); ?>;
    $(function(){
        init();
        initPlaylist();
        initVolumn();
        getMaxDuration();
        bindEvent();
    });
    function nullToZero($value){
        return (typeof $value === "undefined" || $value === null || $value < 0) ? 0 : $value;
    }
    function getFormatTime (sec) { 
        var sec_num = parseInt(nullToZero(sec), 10); // don't forget the second param
        var hours   = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        var seconds = sec_num - (hours * 3600) - (minutes * 60);

        if (hours   < 10) {hours   = "0"+hours;}
        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}
        var time    = hours+':'+minutes+':'+seconds;
        return time;
    }
    function init(){
        $(".dataTables_wrapper").find("div").not(".DataTables_sort_wrapper").hide();
        
        $('th').unbind('click');
        $('th>div .DataTables_sort_icon').remove();
        
        $("td[name=duration]").html(getFormatTime(0));
        
        
        
        $("[name=dsp_name], th[name=pl_desc]").width("100px");
        $("[name=playlist]").width("200px");
        $("[name=duration]").width("10px");
        
        $(".divDetail").height($("#" + $_tableId).height() );
        
        $("#storyName, #storyDesc").css({width:"100%"});
    }
    
    function getPlaylistTemplate(){
        var $selectTemplate = $("<select name='playlist' class='chosen-select' style='width:100%; max-width: 200px;'> <option value='0'></option> </select>");
        
        $.each($_playlist, function($index, $value){
            $selectTemplate.append($("<option ></option>").attr("value", $index).html($value["name"].substring(0, 20)));
        });
        
        return $selectTemplate;
    }
    
    function getVolumnTemplate(){
        return $('<div name="volumnInput" class="volumnInput"></div>');
    }
    
    function initPlaylist(){
        
        var $selectTemplate = getPlaylistTemplate();
        $("#" + $_tableId).find("tbody").find("tr").each(function(){
            if($(this).find(".dataTables_empty").size() > 0)
                return;
            var $dspId = $(this).attr("id").replace("row-", "");
            var $plId = $_storyItemMapPl[$dspId];
            var $select = $selectTemplate.clone(true);
            if(nullToZero($plId) !== 0){
                $select.find('option[value=' + $plId + ']').prop('selected',true);
            }
                
            $(this).find("td[name=playlist]").html($select);
            var $value = $_playlist[$plId];
            var $duration = 0;
            var $desc = "";
            if(nullToZero($value) !== 0){
                $duration = $value["duration"];
                $desc = $value["desc"];
            }            
            $(this).find("td[name=duration]").html(getFormatTime($duration));
            $(this).find("td[name=pl_desc]").html($desc);
        });
        
    }    
    var sliderTooltip =  function( event, ui ) {
//                     $( this ).dequeue();
                            $("#tooltip-volumn")
                            .html(" " + ui.value)
                            .show()
                            .delay(5000)
                            .queue(function(next) {
                                $("#tooltip-volumn").hide().html("");
                                next();
//                                $( this ).dequeue();
                            });
                        }
    function initVolumn(){
        
        var $volumnTemplate = getVolumnTemplate();
        $("#" + $_tableId).find("tbody").find("tr").each(function(){
            if($(this).find(".dataTables_empty").size() > 0)
                return;
            var $volumn = $volumnTemplate.clone(true);
            
            var $dspId = $(this).attr("id").replace("row-", "");
            var volumnValue = $_storyItemMapVolumn[$dspId];
                
                
//            var sliderTooltip = function(event, ui) {
//                var curValue = ui.value || volumnValue;
//                var tooltip = '<div class="slider-tooltip"><div class="slider-tooltip-inner">' + curValue + '</div></div>';
//
//                $('.ui-slider-handle').html(tooltip);
//
//            };
                
            $(this).find("td[name=volumn]").html($volumn);
            
            $(this).find( "div[name=volumnInput]" ).slider({
                range: "min",
                value: parseInt(volumnValue),
                min: 0,
                max: 100,
//                create: sliderTooltip,
                slide: sliderTooltip
            });
          
//            $(this).find( "div[name=volumnInput]" ).slider( "value" , "10");
        });
        
        
//          $( "div[name=volumnInput]" ).slider( "value" );
    }    
    
//    
            
    function bindEvent(){
        bindEventPlaylist();
        bindEventVolumn();
        $("#selectLayout").bind("change", function(e){
            
            clearScreen($_containerId);
            clearRow();
            $("#inputDuration").val(getFormatTime("0"));
            var $lytId = $(this).val();
            $layout = $_layoutAll[$lytId];
            $_layoutWidth = $layout.lyt_width;
            $_layoutHeight = $layout.lyt_height;
            initContainer($_containerId); 
            $.each($_displayAll, function($dspId, $display){
                if($lytId === $display.lyt_ID){
                    createLayout($display.dsp_ID, $display.dsp_name, $display.dsp_top, $display.dsp_left, $display.dsp_width, $display.dsp_height, $display.dsp_zindex);
                    createRow($display.dsp_ID, $display.dsp_name );
                }
            });
//            initPlaylist();
            bindEventPlaylist();
            bindEventVolumn();
        });
    }
    
    function bindEventVolumn(){
        $(".volumnInput").slider({
            range: "min",
//            value: 37,
            min: 0,
            max: 100,
//            slide: function( event, ui ) {
//              $( "#amount" ).val( "$" + ui.value );
//            }
            slide: sliderTooltip
          });
    
    }
    function bindEventPlaylist(){
        $(".chosen-select").chosen().not("#selectLayout").bind("change", function(e){
            var $value = $_playlist[$(this).val()];
            var $duration = $value["duration"];
            var $desc = $value["desc"];
//            $(this).parent("tr").find("td[name=duration]").html($duration);
            var $tr = $(this).parents().filter("tr");
            $tr.find("td[name=duration]").html(getFormatTime($duration));
            $tr.find("td[name=pl_desc]").html($desc);
            
            var $table = $(this).parents().filter("table");
            var highest = -Infinity;
            $table.find("tr").each(function($index, $value){
                var $value = $_playlist[$(this).find("select[name=playlist]").val()];
                
                if($value){
                    var $duration = $value["duration"];
                    highest = Math.max(highest, parseFloat(nullToZero($duration)));
                }
            });
            $("#inputDuration").val(getFormatTime(highest));
//            alert(getFormatTime(highest));
        });
        $('.chzn-container, .chzn-drop').css({"width": "100%"});
        
    };
    
    function getMaxDuration(){
        var highest = -Infinity;
        $("#" + $_tableId).find("tr").each(function($index, $value){
            var $value = $_playlist[$(this).find("select[name=playlist]").val()];

            if($value){
                var $duration = $value["duration"];
                highest = Math.max(highest, parseFloat(nullToZero($duration)));
            }
        });
        $("#inputDuration").val(getFormatTime(highest));
    }
    </script>

    
    
    <?php if(!$this->default_value["permissionEdit"]){ ?>
        <script type="text/javascript" >
            $(function(){
                $(":text").prop({disabled:true}).css({"background-color": "#dedede"});
                $('.chosen-select').prop('disabled', true).trigger('liszt:updated');
            });
        </script>

    <?php } 
