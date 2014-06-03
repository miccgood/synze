

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
    /* #layout1 { background-position: top left; width: 150px; height: 150px; }*/
    /*#layout1, #container { padding: 0.0em; }*/
</style>
<?php $uniqid = uniqid(); ?>
<script>
    var $_layoutTemplate = null;
    var $_layoutCount = 1;
    var $_layoutZIndexCount = 101;
    var $_containerId = "#container";
    var $_layoutWidth = parseInt(layoutWidth);//1280
    var $_layoutHeight = parseInt(layoutHeight);//720
    var $_maxZIndex = -1;
    var $_tableId = "<?php echo $uniqid; ?>";
    
    
    var $_tableTemplate = null;
    var $_trTemplate = $("<tr></tr>");
    var $_tdTemplate = $('<td class="trigger-td-input"></td>');
    var $_isSelect = false;
    
    var $_resWidth = 0;//1280
    var $_resHeight = 0;//720
    $(function() {
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

            var width = parseInt($($_containerId).width());//640px;  360px;
            var height = parseInt($($_containerId).height());
        
            var newLayoutId = "gen-" + $layoutCount;
            
            var widthInit = 200; //$_layoutWidth
            var heightInit = 200; //$_layoutHeight
            
            createLayout(newLayoutId, "Zone", 0, 0, widthInit, heightInit, null);
            
            var $trId = "row-" + newLayoutId;
            createRow($trId, "Zone", 0, 0, widthInit, heightInit, null);
        });

        setCenter($("#layout1"));
    });

    var initContainer = function($containerId){
        
        var width = parseInt($($containerId).width());//640px;  360px;
        var height = parseInt($($containerId).height());
        
        //ถ้าความสูง มากกว่าความกว้าง ให้เปลี่ยน แนวการแสดงผล
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
        bindEvent("#" + id);
        setPosition($newLayout, top, left);
        setSize($newLayout, width, height);
        $_maxZIndex = getMax($_maxZIndex, zindex);
        if(zindex == null || typeof zindex == "undefined"){
            zindex = ++$_maxZIndex;
        }
        setZIndex($newLayout, zindex);
    };
    
    var getMax = function($maxZIndex, $zindex){
        return ($maxZIndex >= $zindex ? $maxZIndex : $zindex);
    }
    
    var createRow = function(id, name, top, left, width, height, zindex) {
        $_tableTemplate.find('.dataTables_empty').hide();
        var $count = $_tableTemplate.find("tr[id]").size();
        var $tr = $_trTemplate.clone(true);
        
        var $tdName = $_tdTemplate.clone(true);
        var $tdLeft = $_tdTemplate.clone(true);
        var $tdTop = $_tdTemplate.clone(true);
        var $tdWidth = $_tdTemplate.clone(true);
        var $tdHeight = $_tdTemplate.clone(true);
        var $tdZindex = $_tdTemplate.clone(true);
        var $tdAction = $_tdTemplate.clone(true);
        
        if(zindex == null || typeof zindex == "undefined"){
            zindex = $_maxZIndex;
        }
        
        $tr.attr("id", id).append($tdName.attr("name", "dsp_name").html(name))
        .append($tdLeft.attr("name", "dsp_left").html(left))
        .append($tdTop.attr("name", "dsp_top").html(top))
        .append($tdWidth.attr("name", "dsp_width").html(width))
        .append($tdHeight.attr("name", "dsp_height").html(height))
        .append($tdZindex.attr("name", "dsp_zindex").html(zindex))
        .append($tdAction.attr("class", "actions ")
                    .html( '<a value="' + id + '" href="javascript:void(0)" class="delete-tr-gen ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button">'+
                        '<span class="ui-button-icon-primary ui-icon ui-icon-circle-minus"></span>'+
                        '<span class="ui-button-text">&nbsp;Delete</span>'+
                     '</a>')
         ).addClass(($count%2 === 0 ? "odd" : "even"))
        ;
        
        $_tableTemplate.append($tr);
        
        bindEventRow();
        bindEventDelete();
    };
    
    var bindEventRow = function(){
        $(".trigger-td-input").unbind().bind("click", function(e){ 
            $_isSelect = true;
            if($(e.target).find('input').size() > 0 ){
                e.preventDefault();
                return ;
            }
            var tdValue = $(e.target).html().trim();
            var tdName = $(e.target).attr("name");

            var $this = $(e.target).html($('<input/>',{
                    type:'text',
                    name: 'input-' + tdName,
                    width: '70px', 
                    value:tdValue 
                }));

            if(tdName == "dsp_left" || tdName == "dsp_width"){
                setSpinner($this, 0, $_layoutWidth);
            } else if(tdName == "dsp_top" || tdName == "dsp_height"){
                setSpinner($this, 0, $_layoutHeight);
            }else if(tdName == "dsp_zindex"){
                setSpinner($this, 0, null);
            }

        });  
        
        bindEventDocument();
    };
    
    var bindEventDelete = function (){
        $(".delete-tr-gen").bind("click", function(){
            var $id = $(this).attr("value");
            removeLayout($id.replace("row-", ""));
            removeRow($id);
        });
    };

    var bindEventDocument = function(){
        $(document).bind("click", function(e1){ 
            if($_isSelect == false){
                return;
            }

            $("table tbody").find("tr").each(function(e2){
                var $isRefresh = false;

                $(this).find('td').each(function(e3){
                    if ($(this).is(":hover")) {
                        return;
                    }
                    if($(this).find('input').size() > 0 ){
                        var inputValue = $(this).find('input').val();
                        if(inputValue != ""){
                            $(this).html(inputValue);
                        } else{
                            //จะเป็นว่างได้ในกรณี ชื่อเท่านั้น
                            $(this).html("Zone");
                        }

                        $isRefresh = true;
                        $_isSelect = false;
                    }
                });


                if($isRefresh == true){
                    var $id = $(this).attr("id").replace("row-", "");
                    //เป็น html() แน่นอนเพราะมีการแปลกลับแล้ว

                    var $name = $(this).find("td[name=dsp_name]").html();
                    var $left = $(this).find("td[name=dsp_left]").html();
                    var $top = $(this).find("td[name=dsp_top]").html();
                    var $width = $(this).find("td[name=dsp_width]").html();
                    var $height = $(this).find("td[name=dsp_height]").html();
                    var $zIndex = $(this).find("td[name=dsp_zindex]").html();
                    refreshLayout($id, $name, $top, $left, $width, $height, $zIndex);
                } 
            });
        });
    };

    var setSpinner = function ($this, min, max){
        var $spinner = $this.find('input').spinner({
            min: min,
            max: max
        }).button().css({padding: "0px", margin: "0px"});


        $spinner.keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                 // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) || 
                 // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        }).keyup(function(e){
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }else{
                var $spinnerValue = $(this).spinner("value");
                if($spinnerValue > max){
                    $(this).spinner("value", max);
                }
                if($spinnerValue < min){
                    $(this).spinner("value", min);
                }
            }
        });
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


// $( "#layout1" ).css({top: "auto", left: "auto"});
</script>
<div class='clear' style="width: 100%; height: 20px;"></div>
<div style="width: 100%;">

    <div id="container" class="ui-widget-content">
        <div id="layout1" class="ui-state-active layout-default" style="display:none;">
            <span class="middle">
                Layout1
            </span>
        </div>
    </div>
   

</div>
<div class='clear' style="width: 100%; height: 20px;"></div>


<!-- <br/>
    X : <span id="printX">0</span>
    Y : <span id="printY">0</span> 

    Width : <span id="printWidth">0</span>
    Height : <span id="printHeight">0</span> 
    <br/>-->
    

    <?php if($this->default_value["permissionEdit"]){ ?>
       
        <div>


            <span class="datatables-add-button">
                <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" >
                        <span class="ui-button-icon-primary ui-icon ui-icon-circle-check"></span>
                        <span class="ui-button-text" id="saveLayout"> Update Change </span>
                        <!--<button id="addLayout"> Add Layout </button>-->
                </a>
            </span>

            <span class="datatables-add-button">
                <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" >
                        <span class="ui-button-icon-primary ui-icon ui-icon-circle-check"></span>
                        <span class="ui-button-text" id="save_and_go_back_to_list"> Update and go back to list </span>
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

        <br/>

        </div>

     <?php 
                    } else {  ?>
    <div class='buttons-box'>


        <?php if(!is_null($back_url) && $back_url != ""){?>

            <span class="datatables-add-button">
                <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary back-to-list" href="<?php echo $back_url?>" onclick="return confirmGoBackToList()">
                        <span class="ui-button-icon-primary ui-icon ui-icon-arrow-1-w"></span>
                        <span class="ui-button-text">Back</span>
                </a>
            </span>
        <?php }?>

        <div class='clear'></div>
    </div>
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


                        
                        
<div class='clear' style="width: 100%; height: 20px;"></div>

<div>

    <table cellpadding="0" cellspacing="0" border="0" class="display groceryCrudTable" id="<?php echo $uniqid; ?>" style="border: 2px solid #E2E4FF;">
        <thead >
            <tr>
                <?php foreach ($columns as $column) { ?>

            <!---				<th><?php echo $column->display_as; ?></th>-->
                    <?php $text_align = (empty($column->align) ? '' : ('style="text-align:' . $column->align . '"')); ?>
                    <th <?php echo $text_align; ?>><?php echo $column->display_as; ?></th>
                <?php } ?>
                <?php if (!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)) { ?>
                    <th class='actions'><?php echo $this->l('list_actions'); ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($list as $num_row => $row) { ?>
                <tr id='row-<?php echo $row->dsp_ID ?>'>
                    <?php foreach ($columns as $column) { ?>
                                            <!--<td><?php echo $row->{$column->field_name} ?></td>-->
                        <?php $text_align = (empty($column->align) ? '' : ('align="' . $column->align . '"')); ?>
                        
                                <!--<td <?php echo $text_align; ?> name="<?php echo $column->field_name ?>"><?php echo $row->{$column->field_name} ?></td>-->
                                <td <?php echo $text_align; ?> name="<?php echo $column->field_name ?>" class="trigger-td-input">
                                    <?php echo $row->{$column->field_name} ?>
                                </td>
                    <?php } ?>
                    <?php if (!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)) { ?>
                        <td class='actions'>
                            <?php
                            if (!empty($row->action_urls)) {
                                foreach ($row->action_urls as $action_unique_id => $action_url) {
                                    $action = $actions[$action_unique_id];
                                    ?>
                                    <a href="<?php echo $action_url; ?>" class="edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button">
                                        <span class="ui-button-icon-primary ui-icon <?php echo $action->css_class; ?> <?php echo $action_unique_id; ?>"></span><span class="ui-button-text">&nbsp;<?php echo $action->label ?></span>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                            <?php if (!$unset_read) { ?>
                                <a href="<?php echo $row->read_url ?>" class="edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button">
                                    <span class="ui-button-icon-primary ui-icon ui-icon-document"></span>
                                    <span class="ui-button-text">&nbsp;<?php echo $this->l('list_view'); ?></span>
                                </a>
                            <?php } ?>

                            <?php if (!$unset_edit) { ?>
                                <a href="<?php echo $row->edit_url ?>" class="edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button">
                                    <span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span>
                                    <span class="ui-button-text">&nbsp;<?php echo $this->l('list_edit'); ?></span>
                                </a>
                            <?php } ?>
                            <?php if (!$unset_delete) { ?>
                                <a onclick = "javascript: return delete_row('<?php echo $row->delete_url ?>', '<?php echo $row->dsp_ID ?>')"
                                   href="javascript:void(0)" class="delete_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button">
                                    <span class="ui-button-icon-primary ui-icon ui-icon-circle-minus"></span>
                                    <span class="ui-button-text">&nbsp;<?php echo $this->l('list_delete'); ?></span>
                                </a>
                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
                
                <script type="text/javascript">
//                    var newLayoutId = "layout" + (++$_layoutCount);
                    
                    $(function(){
                        
                        
                        createLayout('<?php echo $row->dsp_ID ?>', '<?php echo $row->dsp_name ?>'
                                    ,<?php echo $row->dsp_top ?>, <?php echo $row->dsp_left ?>
                                    ,<?php echo $row->dsp_width ?>, <?php echo $row->dsp_height ?>, <?php echo $row->dsp_zindex ?>);
                                
                        bindEventRow();
                        
                    });
//                    $("")
                </script>
                        
            <?php } ?>
        </tbody>
        <tfoot style="display:none;">
            <tr>
                <?php foreach ($columns as $column) { ?>
                    <th><input type="text" name="<?php echo $column->field_name; ?>" placeholder="<?php echo $this->l('list_search') . ' ' . $column->display_as; ?>" class="search_<?php echo $column->field_name; ?>" /></th>
                <?php } ?>
                <?php if (!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)) { ?>
                    <th>
                        <button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only floatR refresh-data" role="button" data-url="<?php echo $ajax_list_url; ?>">
                            <span class="ui-button-icon-primary ui-icon ui-icon-refresh"></span><span class="ui-button-text">&nbsp;</span>
                        </button>
                        <a href="javascript:void(0)" role="button" class="clear-filtering ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary floatR">
                            <span class="ui-button-icon-primary ui-icon ui-icon-arrowrefresh-1-e"></span>
                            <span class="ui-button-text"><?php echo $this->l('list_clear_filtering'); ?></span>
                        </a>
                    </th>
                <?php } ?>
            </tr>
        </tfoot>
    </table>

</div>

<script type="text/javascript">
    $(function(){
        $(".dataTables_wrapper").find("div").not(".DataTables_sort_wrapper").hide();
        $('th').eq(-2).click();
        $('th').unbind('click');
        $('th>div .DataTables_sort_icon').remove();
    });
    
    function callbackAfterDelete(id){
        $('#'+id).remove();
    }
    
</script>






    <?php if(!$this->default_value["permissionEdit"]){ ?>
       <script type="text/javascript" >
            $(function(){
                
                $("#" + $_tableId + " td , " + $_containerId + " .layout-default").unbind();
    //            $(".datatables-add-button, .datepicker-input-clear").hide();
    //            $(":text").prop({disabled:true}).css({"background-color": "#dedede"});
    //            $('#select_resolution').prop('disabled', true).trigger('liszt:updated');
    //
    //            $(".ui-multiselect li").unbind();
    //            $(".ui-multiselect select").prop({disabled:true});
    //            $(".ui-multiselect .ui-icon").hide();
            });
        </script>   

    <?php }?>