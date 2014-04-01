<?php

	$this->set_css($this->default_theme_path.'/datatables/css/demo_table_jui.css');
	$this->set_css($this->default_css_path.'/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS);
	$this->set_css($this->default_theme_path.'/datatables/css/datatables.css');
	$this->set_css($this->default_theme_path.'/datatables/css/jquery.dataTables.css');
	$this->set_css($this->default_theme_path.'/datatables/extras/TableTools/media/css/TableTools.css');
	$this->set_js_lib($this->default_javascript_path.'/'.grocery_CRUD::JQUERY);

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
	$this->set_js_lib($this->default_javascript_path.'/common/lazyload-min.js');

	if (!$this->is_IE7()) {
		$this->set_js_lib($this->default_javascript_path.'/common/list.js');
	}

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS);
	$this->set_js_lib($this->default_theme_path.'/datatables/js/jquery.dataTables.min.js');
	$this->set_js($this->default_theme_path.'/datatables/js/datatables-extras.js');
	$this->set_js($this->default_theme_path.'/datatables/js/datatables.js');
	$this->set_js($this->default_theme_path.'/datatables/extras/TableTools/media/js/ZeroClipboard.js');
	$this->set_js($this->default_theme_path.'/datatables/extras/TableTools/media/js/TableTools.min.js');

	/** Fancybox */
	$this->set_css($this->default_css_path.'/jquery_plugins/fancybox/jquery.fancybox.css');
	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.fancybox-1.3.4.js');
	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.easing-1.3.pack.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.chosen.min.js');
        $this->set_css($this->default_css_path.'/jquery_plugins/chosen/chosen.css');
                
                
?>
<script type='text/javascript'>
	var base_url = '<?php echo base_url();?>';
	var subject = '<?php echo $subject?>';

	var unique_hash = '<?php echo $unique_hash; ?>';

	var displaying_paging_string = "<?php echo str_replace( array('{start}','{end}','{results}'),
		array('_START_', '_END_', '_TOTAL_'),
		$this->l('list_displaying')
	   ); ?>";
	var filtered_from_string 	= "<?php echo str_replace('{total_results}','_MAX_',$this->l('list_filtered_from') ); ?>";
	var show_entries_string 	= "<?php echo str_replace('{paging}','_MENU_',$this->l('list_show_entries') ); ?>";
	var search_string 			= "<?php echo $this->l('list_search'); ?>";
	var list_no_items 			= "<?php echo $this->l('list_no_items'); ?>";
	var list_zero_entries 			= "<?php echo $this->l('list_zero_entries'); ?>";

	var list_loading 			= "<?php echo $this->l('list_loading'); ?>";

	var paging_first 	= "<?php echo $this->l('list_paging_first'); ?>";
	var paging_previous = "<?php echo $this->l('list_paging_previous'); ?>";
	var paging_next 	= "<?php echo $this->l('list_paging_next'); ?>";
	var paging_last 	= "<?php echo $this->l('list_paging_last'); ?>";

	var message_alert_delete = "<?php echo $this->l('alert_delete'); ?>";

	var default_per_page = <?php echo $default_per_page;?>;

	var unset_export = <?php echo ($unset_export ? 'true' : 'false'); ?>;
	var unset_print = <?php echo ($unset_print ? 'true' : 'false'); ?>;

	var export_text = '<?php echo $this->l('list_export');?>';
	var print_text = '<?php echo $this->l('list_print');?>';

	<?php
	//A work around for method order_by that doesn't work correctly on datatables theme
	//@todo remove PHP logic from the view to the basic library
	$ordering = 0;
	$sorting = 'asc';
	if(!empty($order_by))
	{
		foreach($columns as $num => $column) {
			if($column->field_name == $order_by[0]) {
				$ordering = $num;
				$sorting = isset($order_by[1]) && $order_by[1] == 'asc' || $order_by[1] == 'desc' ? $order_by[1] : $sorting ;
			}
		}
	}
	?>

	var datatables_aaSorting = [[ <?php echo $ordering; ?>, "<?php echo $sorting;?>" ]];

        <?php echo $this->customScript;?>
</script>
<?php
	if(!empty($actions)){
?>
	<style type="text/css">
		<?php foreach($actions as $action_unique_id => $action){?>
			<?php if(!empty($action->image_url)){ ?>
				.<?php echo $action_unique_id; ?>{
					background: url('<?php echo $action->image_url; ?>') !important;
				}
			<?php }?>
		<?php }?>
	</style>
<?php
	}
?>
<?php if($unset_export && $unset_print){?>
<style type="text/css">
	.datatables-add-button
	{
		position: static !important;
	}
</style>
<?php }?>
<div id='list-report-error' class='report-div error report-list'></div>
<div id='list-report-success' class='report-div success report-list' <?php if($success_message !== null){?>style="display:block"<?php }?>><?php
 if($success_message !== null){?>
	<p><?php echo $success_message; ?></p>
<?php }
?></div>
<?php if(!$unset_add){?>
<span class="datatables-add-button">
<a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="<?php echo $add_url?>">
	<span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>
	<span class="ui-button-text"><?php echo $this->l('list_add'); ?> <?php echo $subject?></span>
</a>
</span>
<?php }?>
<?php if(!is_null($back_url) && $back_url != ""){?>

<span class="datatables-add-button">
<a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="<?php echo $back_url?>">
	<span class="ui-button-icon-primary ui-icon ui-icon-circle-arrow-w"></span>
	<span class="ui-button-text">Back</span>
</a>
</span>
<?php }?>
<!--<div style='text-align: center;'><h3 style="color: #222;">  Table <?php echo $subject?> </h3></div>-->
<div class="dataTablesContainer">
	<?php echo $list_view?>
</div>

<?php // echo var_dump($this); ?>




<script type="text/javascript">
    $(function(){
        $("#save").unbind().bind("click", function(){
            if(!validate()){
//                alert("validate Error");
                return false;;
            }
            var url = "storyitem/ajax";
            var dataToBeSent = new Array();
             
            dataToBeSent["story"] = getStoryFromPage();
            
            dataToBeSent["storyItem"] = getDataFromTable();
//            var data = { name: "John", location: "Boston" };

            dataToBeSent = arrayToObject(dataToBeSent);
            
            $("#FormLoading").show();
            $.post(url, dataToBeSent, function(data, textStatus) {
               console.log( "success" );
              }, "json")
            .done(function(data) {
                
                form_success_message("<p>Your data has been successfully updated. <a href='<?php echo base_url("index.php/story");?>'>Go back to list</a></p>") ;
//                $("#report-success").fadeIn(1000).delay(3000).fadeOut(1000);
//                location.reload();
            })
            .fail(function(data) {
                form_error_message(data);
//                $("#report-error").fadeIn(1000).delay(3000).fadeOut(1000);
            })
            .always(function() {
                $("#FormLoading").hide();
                console.log( "complete" );
            });
        });
    });
    
    function validate(){
        
        
        var storyNameId = "#storyName";
        var selectLayoutId = "#selectLayout";
        var message = "";
        var ret = true;
        $(storyNameId + ', ' + selectLayoutId).removeClass('field_error');
        
        var $storyName = $(storyNameId).val();
        
        if($storyName === null || $storyName === ""){
//            $("#storyName").focus().css({'border-color': "red"});
            
            $(storyNameId).addClass('field_error');
            message += "<p>The Story Name field is required.</p>";
            ret = false;
        }
        var $selectLayout = $(selectLayoutId).val();
        
        if($selectLayout === null || $selectLayout === "" || $selectLayout === "0"){
            $(selectLayoutId).addClass('field_error');//$("#selectLayout").focus().css('border-color', "red");
            message += "<p>The Layout field is required.</p>";
            ret = false;
        }
        
        if(!ret){
            form_error_message(message);
        }
        
        return ret;
    }
    
    function getDataFromTable(){
        var $ret = new Array();
//        ค้นหา tr 
        $("#" + $_tableId + " tbody").find("tr").each(function(e2){
            var $id = $(this).attr("id");
            if($id){
                var storyItem = new Object();
                storyItem.dsp_ID = $id.replace("row-", "");
                storyItem.pl_ID = $(this).find("select[name=playlist]").val();
                $ret[$ret.length] = storyItem;
            }
            
        });
        return arrayToObject($ret);
    }
    
    function getStoryFromPage(){
        var $story = new Array();
        $story["story_id"] = $("#storyId").val();
        $story["story_name"] = $("#storyName").val();
        $story["story_desc"] = $("#storyDesc").val();
        $story["lyt_ID"] = $("#selectLayout").val();
        return arrayToObject($story);
    }
    
//    function arrayToObject($ret){
//        var jObject={};
//        for(var i = 0 ; i < $ret.length ; i++)
//        {
//            jObject[i] = $ret[i];
//        }
//        return  jObject;
//    }
//    
    function arrayToObject($ret){
        var jObject={};
        for(i in $ret)
        {
            jObject[i] = $ret[i];
        }
        return  jObject;
    }
</script>

<!--<div class='line-1px'></div>-->
<div id='report-error' class='report-div error'><p>Your data has been Error updated. </p></div>
<div id='report-success' class='report-div success'><p>Your data has been successfully updated. <a href="<?php echo base_url("index.php/story");?>">Go back to list</a></p></div>