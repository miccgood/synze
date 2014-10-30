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


<?php if(!is_null($back_url) && $back_url != ""){?>

<!--<span class="datatables-add-button">
<a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="<?php echo $back_url?>">
	<span class="ui-button-icon-primary ui-icon ui-icon-circle-arrow-w"></span>
	<span class="ui-button-text">Back</span>
</a>
</span>-->
<?php }?>

<?php if(!$unset_add){?>
<!--<span class="datatables-add-button">
<a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="<?php echo $add_url?>">
	<span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>
	<span class="ui-button-text"><?php echo $this->l('list_add'); ?> <?php echo $subject?></span>
</a>
</span>-->
<?php }?>

<?php if($this->default_value["permissionEdit"]){ ?>
		
<span class="datatables-add-button">
<a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" >
	<span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>
	<span class="ui-button-text" id="addLayout"> Add Zone </span>
        <!--<button id="addLayout"> Add Layout </button>-->
</a>
</span>
        
<?php } ?>
        


<!--<span class="datatables-add-button">
<a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" >
	<span class="ui-button-icon-primary ui-icon ui-icon-circle-check"></span>
	<span class="ui-button-text" id="saveLayout"> Save </span>
        <button id="addLayout"> Add Layout </button>
</a>
</span>-->



<!--<div style='text-align: center;'><h3 style="color: #222;">  Table <?php echo $subject?> </h3></div>-->
<div class="dataTablesContainer">
	<?php echo $list_view?>
</div>

<?php // echo var_dump($this); ?>


<script type="text/javascript">
    $(function(){
        var save_and_go_back_to_list = false;
        $("#save_and_go_back_to_list").unbind().bind("click", function(){
            save_and_go_back_to_list = true;
            $("#saveLayout").click();
        });
        $("#saveLayout").unbind().bind("click", function(){
            if(!validate()){
                alert("validate Error");
            }
            var url = "display/ajax";
            var dataToBeSent = getDataFromTable();
            var data = { name: "John", location: "Boston" };
            $.post(url, dataToBeSent, function(data, textStatus) {
               console.log( "success" );
              }, "json")
            .done(function() {
                if(save_and_go_back_to_list){
                    window.location = "<?php echo $back_url?>";
                }else{
                    document.cookie="show=success";
                    location.reload();
                }
                
            })
            .fail(function() {
                 $("#report-error").fadeIn(1000).delay(3000).fadeOut(1000);
            })
            .always(function() {
              console.log( "complete" );
            });
        });
        var isShow = getCookie("show");
        if(isShow != "" && isShow == "success"){
            document.cookie="show=false";
            $("#report-success").fadeIn(1000).delay(3000).fadeOut(1000);
        }
       
        
    });
    
    function getCookie(cname)
    {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) 
          {
          var c = ca[i].trim();
          if (c.indexOf(name)==0) return c.substring(name.length,c.length);
          }
        return "";
    }

    function validate(){
        return true;
    }
    
    function getDataFromTable(){
        var $ret = new Array();
//        ค้นหา tr 
        $("table tbody").find("tr").each(function(e2){
            var $id = $(this).attr("id");
            if($id){
                var zone = new Object();
                zone.dsp_ID = $(this).attr("id").replace("row-", "");

    //            หา td
                $(this).find('td').each(function(e3){
                    var name = $(this).attr("name");
                    if(typeof name === "undefined" || name === "undefined"){
                        return ;
                    }
                    
    //                ตรวจสอบว่า เป็น select หรือไม่ จะได้ดึงข้อมูลมาถูก
                    if($(this).find('select').size() > 0 ){
                        var inputValue = $(this).find("select").val();
                        zone[name] = inputValue.replace("row-", "").trim();
                    } else 
    //                ตรวจสอบว่า เป็น input หรือไม่ จะได้ดึงข้อมูลมาถูก
                    if($(this).find('input').size() > 0 ){
                        var inputValue = $(this).find('input').val();
                        zone[name] = inputValue.trim();
                    }else{
                        var tdValue = $(this).html();
                        zone[name] = tdValue.trim();
                    }
                });
                $ret[$ret.length] = zone;
            }
        });
        var jObject={};
        for(i in $ret)
        {
            jObject[i] = $ret[i];
        }
        return  jObject;
    }
    
</script>

<div class='line-1px'></div>
<div id='report-error' class='report-div error'><p>Your data has been Error updated. </p></div>
<div id='report-success' class='report-div success'><p>Your data has been successfully updated. <a href="http://localhost/synzes/index.php/layout/index">Go back to list</a></p></div>